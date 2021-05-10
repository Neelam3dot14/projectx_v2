<?php

namespace App\Jobs;

use App\Events\KeywordEvents;
use App\Models\Internal\CampaignKeyword;
use App\Models\Internal\AlertRevision;
use App\Repositories\CrawlRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\RateLimitedMiddleware\RateLimited;

class CrawlKeyword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $keyword, $alertRevision, $crawlingData, $metadata;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CampaignKeyword $keyword, AlertRevision $alertRevision = null, $metadata = [], $currentCrawler = false)
    {
        $this->onQueue('crawler');
        $this->keyword = $keyword;
        $this->alertRevision = $alertRevision;
        $this->metadata = $metadata;
        $this->crawlingData = [
            'keyword' => $this->keyword,
        ];
        if($currentCrawler){
            $this->crawlingData['crawler'] = $currentCrawler;
        }
    }

    /*
    * Determine the time at which the job should timeout.
    *
    */
    public function retryUntil()
    {
        return now()->addHours(2);
    }

    public function middleware()
    {
        $shouldRateLimitJobs = config('api.rate_limiter');
        $rateLimitedMiddleware = (new RateLimited())
            ->enabled($shouldRateLimitJobs)
            ->allow(10)
            ->everyMinute()
            ->releaseAfterSeconds(120);

        return [$rateLimitedMiddleware];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->crawler = new CrawlRepository();
        $startTime =  microtime(true);
        $response = $this->crawler->crawl($this->crawlingData, $this->alertRevision);
        $crawlerArray = $response['crawlerArray'];
        $response = $response['response'];
        $elapsed_time = number_format(microtime(true) - $startTime,4);

        $crawler = $this->getCrawler();
        $status = $response->status();
        //parse crawler metadata
        $this->metadata = $this->parseCrawlerMetadata($crawler, $elapsed_time, $status, $crawlerArray);

        if ( $response->status() == 200 && $this->checkIfHasHtml( $response->body() ) ){
            if( $this->checkIfCrawlerDetected($response->body()) !== false ){
                $message = "Crawler API Detected unusual traffic from your computer network";
                $error = $this->parseErrorResponse($message, $crawler, $crawlerArray);
                $alert_id = $this->saveErrorCrawlAlert($this->keyword, $error);
                $this->retryCrawlingJob($alert_id);
            } else {
                $alert_id = $this->saveSuccessCrawlAlert($this->keyword, $response->body());
                event(KeywordEvents::KEYWORD_CRAWLED, new KeywordEvents($this->keyword, $alert_id));
            }
        } elseif ($response->status() == 501) {
            $alert_id = $this->saveErrorCrawlAlert($this->keyword, $response->content());
            $this->retryCrawlingJob($alert_id);
        } elseif ($response->status() == 401) {
            $message = 'Unauthorized Access API Key Error '. $response->body();
            $error = $this->parseErrorResponse($message, $crawler, $crawlerArray);
            $alert_id = $this->saveErrorCrawlAlert($this->keyword, $error);
        }
        else{
            $error = $this->parseErrorResponse($response->body(), $crawler, $crawlerArray);
            $alert_id = $this->saveErrorCrawlAlert($this->keyword, $error);
            $this->retryCrawlingJob($alert_id);
        }
    }

    public function parseCrawlerMetadata($crawler, $elapsed_time, $status, $crawlerArray)
    {
        return [
            'CRAWLER' => $crawler,
            'ElAPSED_TIME' => $elapsed_time,
            'API_STATUS' => $status,
            'CRAWL_URL'  => $crawlerArray['crawl_url'],
            'CRAWL_AGENT' => isset($crawlerArray['user_agent']) ? $crawlerArray['user_agent'] : 'none',
            'PROXY' => $crawlerArray['USE_PROXY'],
        ];
    }

    public function parseErrorResponse($error_msg, $crawler, $crawlerArray)
    {
        return [
            'CODE' => 'CRAWL_FAILURE_DETECTED',
            'MESSAGE' => $error_msg,
            'CRAWLER' => $crawler,
            'CRAWL_URL'  => $crawlerArray['crawl_url'],
            'CRAWL_AGENT' => isset($crawlerArray['user_agent']) ? $crawlerArray['user_agent'] : 'none',
            'PROXY' => $crawlerArray['USE_PROXY'],
        ];
    }

    public function saveErrorCrawlAlert(CampaignKeyword $keyword, $error_response){
        $this->keyword = $keyword;

        if($this->alertRevision){
            $crawling_error = isset($this->alertRevision->crawling_error) ? $this->alertRevision->crawling_error .",". json_encode($error_response) : json_encode($error_response);
            $metadata = json_encode($this->metadata);
            $this->alertRevision->crawling_error = $crawling_error;
            $this->alertRevision->crawler_metadata = $metadata;
            $this->alertRevision->status = 'CRAWL_FAILED';
            $this->alertRevision->crawler_tries = ++$this->alertRevision->crawler_tries;
            $this->alertRevision->save();
            return $this->alertRevision->id;
        }
    }

    public function saveSuccessCrawlAlert(CampaignKeyword $keyword, $response)
    {
        $this->keyword = $keyword;
        if($this->alertRevision){
            $this->alertRevision->crawled_html = $response;
            $this->alertRevision->crawler_metadata = json_encode($this->metadata);
            $this->alertRevision->status = 'CRAWLED';
            $this->alertRevision->crawler_tries = ++$this->alertRevision->crawler_tries;
            $this->alertRevision->save();
            return $this->alertRevision->id;
        }
    }

    public function retryCrawlingJob($alert_id)
    {
        $this->alertRevision = AlertRevision::find($alert_id);
        if ($this->checkTries($this->alertRevision->crawler_tries)){
            $currentCrawler = $this->getCrawler();
            $this->alertRevision->status = 'CRAWLING';
            $this->alertRevision->save();
            CrawlKeyword::dispatch($this->keyword, $this->alertRevision, $currentCrawler)
                ->delay(now()->addMinutes(1));
        } else{
            $this->alertRevision->status = 'CRAWL_FAILED';
            $this->alertRevision->save();
            $this->fail();
            $this->delete();
        }
    }

    public function checkIfHasHtml($html)
    {
        return preg_match('/<html.*>/', $html);
    }

    public function checkIfCrawlerDetected($html)
    {
        return strpos($html, 'unusual traffic from your computer network');
    }

    public function checkTries($tries)
    {
        if($tries < config('api.crawler.tries')){
            return true;
        } else {
            return false;
        }
    }

    public function getCrawler()
    {
        return isset($this->crawlingData['crawler']) ? $this->crawlingData['crawler'] : 'No Crawler Detected';
    }

//    public function failed($exception = null)
//    {
//        $crawler = $this->getCrawler();
//        $message = isset($exception) ? $exception->getMessage() : 'manually failed jobs';
//        $error = $this->parseErrorResponse($message, $crawler, $crawlerArray);
//        $alert_id = $this->saveErrorCrawlAlert($this->keyword, $error);
//        $this->retryCrawlingJob($alert_id);
//    }

}
