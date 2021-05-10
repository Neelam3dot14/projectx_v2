<?php

namespace App\Jobs;

use App\Events\KeywordEvents;
use App\Models\Internal\CampaignKeyword;
use App\Models\Internal\AlertRevision;
use App\Models\Internal\Keyword\KeywordAd;
use App\Repositories\ScrapeRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\RateLimitedMiddleware\RateLimited;

class ScrapeKeyword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $keyword, $scraper, $alertRevision, $metadata, $browser;

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
    public function __construct(CampaignKeyword $keyword, AlertRevision $alertRevision, $metadata = [])
    {
        $this->onQueue('scraper');
        $this->keyword = $keyword;
        $this->alertRevision = $alertRevision;
        $this->metadata = $metadata;
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
            ->allow(20)
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
        if(empty($this->alertRevision->crawled_html)){
            return;
        }
        $this->scraper = new ScrapeRepository();
        $startTime =  microtime(true);
        $response = $this->scraper->scrape($this->alertRevision);
        $browser = $response['browser'];
        $response = $response['response'];
        $elapsed_time = number_format(microtime(true) - $startTime,4);
        //saving Scraper Metadata
        $status = $response->status();
        $this->metadata = $this->parseScraperMetadata($elapsed_time, $status, $browser);

        if ($response->status() == 200){
            $this->saveSuccessResponse($response->content());
            event(KeywordEvents::KEYWORD_SCRAPED, new KeywordEvents($this->keyword, $this->alertRevision->id));
        } elseif ($response->status() == 501) {
            $error_response = $this->parseErrorResponse("INTERNAL_SERVER_ERROR_DETECTED", $response->content());
            $this->saveErrorResponse($error_response);
            $this->retryScrapingJob($this->alertRevision);
        } elseif ($response->status() == 581) {
            $error_response = $this->parseErrorResponse("SCRAPE_ERROR_DETECTED", $response->content());
            $this->saveErrorResponse($error_response);
        } elseif ($response->status() == 582) {
            $error_response = $this->parseErrorResponse("SCRAPE_DATA_ERROR_DETECTED", $response->content());
            $this->saveErrorResponse($error_response);
        }
        else{
            $error_response = $this->parseErrorResponse($response->getReasonPhrase()."_DETECTED", $response->body());
            $this->saveErrorResponse($error_response);
            $this->retryScrapingJob($this->alertRevision);
        }
    }

    public function parseErrorResponse($error_code, $error_msg)
    {
        return [
            'CODE' => $error_code,
            'MESSAGE' => $error_msg,
            'SCRAPER' => 'NODEX SCRAPER',
        ];
    }

    public function parseScraperMetadata($elapsed_time, $status, $browser)
    {
        return [
            'SCRAPER' => 'NODEX SCRAPER',
            'ElAPSED_TIME' => $elapsed_time,
            'API_STATUS' => $status,
            'BROWSER' => $browser,
        ];
    }

    public function saveErrorResponse($error_response){
        $this->alertRevision->scraping_error = isset($this->alertRevision->scraping_error) ? $this->alertRevision->scraping_error .",". json_encode($error_response) : json_encode($error_response);
        $this->alertRevision->scraper_metadata = json_encode($this->metadata);
        $this->alertRevision->status = 'SCRAPING_FAILED';
        $this->alertRevision->scraper_tries = ++$this->alertRevision->scraper_tries;
        $this->alertRevision->save();
    }

    public function saveSuccessResponse($success_response){
        $this->alertRevision->scraped_json = $success_response;
        $this->alertRevision->scraper_metadata = json_encode($this->metadata);
        $this->alertRevision->status = 'SCRAPED';
        $this->alertRevision->scraper_tries = ++$this->alertRevision->scraper_tries;
        $this->alertRevision->save();
        $this->saveKeywordAds($this->alertRevision);
    }

    public function retryScrapingJob(AlertRevision $alertRevision)
    {
        $this->alertRevision = $alertRevision;
        if ($this->checkTries($this->alertRevision->scraper_tries)){
            $this->alertRevision->status = 'SCRAPING';
            $this->alertRevision->save();
            ScrapeKeyword::dispatch($this->keyword, $this->alertRevision)
                ->delay(now()->addMinutes(1));
        } else{
            $this->alertRevision->status = 'SCRAPING_FAILED';
            $this->alertRevision->save();
            $this->fail();
            $this->delete();
        }
    }

    public function checkTries($tries)
    {
        if($tries < config('api.scraper.tries')){
            return true;
        } else {
            return false;
        }
    }

    public function saveKeywordAds(AlertRevision $alertRevision)
    {
        $this->alertRevision = $alertRevision;
        if(empty($this->alertRevision->scraped_json)){
            return false;
        }

        $response = json_decode($this->alertRevision->scraped_json, true);
        $adResults = $response['ad_results'];
        $adData = [];
        foreach ($adResults as $adItem) {
            $item = [
                'alert_id' => $this->alertRevision->id,
                'keyword_id' => $this->alertRevision->keyword_id,
                'keyword_group_id' => $this->alertRevision->keyword_group_id,
                'ad_type' => 'main',
                'ad_position' => $adItem['position'],
                'link' => $adItem['link'],
                'ad_visible_link' => $adItem['visible_link'],
                'ad_link' => $adItem['tracking_link'],
                'ad_title' => $adItem['title'],
                'ad_text' => $adItem['snippet'],
                'ad_status' => 'PENDING',
            ];
            $keyword_ad = KeywordAd::create($item);
            if(!empty($adItem['sub_links'])){
                foreach ($adItem['sub_links'] as $subitem){
                    $item = [
                        'alert_id' => $this->alertRevision->id,
                        'keyword_id' => $this->alertRevision->keyword_id,
                        'keyword_group_id' => $this->alertRevision->keyword_group_id,
                        'parent_id' => $keyword_ad->id,
                        'ad_type' => 'sub',
                        'ad_position' => $adItem['position'],
                        'link' => $subitem['link'],
                        'ad_visible_link' => $adItem['visible_link'],
                        'ad_link' => $subitem['tracking_link'],
                        'ad_title' => $adItem['title'],
                        'ad_text' => empty($subitem['snippet']) ? $subitem['title'] : $subitem['snippet'],
                        'ad_status' => 'PENDING',
                    ];
                    $sub_keyword_ad = KeywordAd::create($item);
                }
            }
        }
    }
    
    public function failed($exception = null)
    {
        $message = isset($exception) ? $exception->getMessage() : 'manually failed jobs';
        $error_response = $this->parseErrorResponse("SCRAPE_JOB_FAILURE_DETECTED", $message);
        $this->saveErrorResponse($error_response);
        $this->retryScrapingJob($this->alertRevision);
    }
}
