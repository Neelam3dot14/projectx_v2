<?php


namespace App\Repositories\Crawlers;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapeDogCrawler extends BaseCrawler
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('api.crawler.apis.scrapedog.key');
    }

    public function crawl($dataArray, $alertRevision)
    {
        $crawlerArray = [
            'crawl_url' => $this->buildCrawlerUrl($dataArray, $alertRevision),
            'USE_PROXY' => false,
            'user_agent' => $this->getUserAgent($alertRevision),
        ];
        $response = $this->executeCrawlApi($crawlerArray);
        return ['response'=> $response, 'crawlerArray' => $crawlerArray ];
    }

    public function executeCrawlApi($crawlData)
    {
        $crawlUrl = $crawlData['crawl_url'];
        $user_agent = $crawlData['user_agent'];
        $api_url = "http://api.scrapingdog.com/scrape";
        try{
            $response = Http::withHeaders([
                'User-Agent' => $user_agent,
                //'version' => '1.0',
            ])->withOptions([
                'debug' => false,
                'version' => '1.0',
                //'Expect' => ''
            ])->timeout(config('api.crawler.timeout'))->get($api_url, [
                'api_key' => $this->apiKey,     //Test api key '601a8af41455604388503b92'
                'url' => $crawlUrl,
                'custom_headers' => config('api.crawler.header'),
            ]);
            //Log::debug($response->body(), ['ScrapeDogURL', $crawlUrl]);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'CODE' => 'CRAWL_FAILURE_DETECTED',
                'MESSAGE' => $e->getMessage(),
                'CRAWLER' => "ScrapeDogCrawler",
                'CRAWL_URL'  => $crawlUrl,
                'CRAWL_AGENT' =>  $user_agent,
                'PROXY' => $crawlData['USE_PROXY'], 
            ], 501);
        }
    }
}
