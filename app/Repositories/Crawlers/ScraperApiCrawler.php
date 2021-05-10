<?php


namespace App\Repositories\Crawlers;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScraperApiCrawler extends BaseCrawler
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('api.crawler.apis.scraperapi.key');
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
        $api_url = "http://api.scraperapi.com";

        try{
            $response = Http::withHeaders([
                'User-Agent' => $user_agent,
                //'version' => '1.0',
            ])->withOptions([
                'debug' => false,
                'version' => '1.0',
                //'Expect' => ''
            ])->timeout(config('api.crawler.timeout'))->get($api_url, [
                'api_key' => $this->apiKey,  //test key '31521fdffa22a4c0511450756b47d81b'
                'url' => $crawlUrl,
                'keep_headers' => config('api.crawler.header'),
            ]);
            //Log::debug($response->body(), ['crawlURL', $crawlUrl]);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'CODE' => 'CRAWL_FAILURE_DETECTED',
                'MESSAGE' => $e->getMessage(),
                'CRAWLER' => 'ScrapeApiCrawler',
                'CRAWL_URL'  => $crawlUrl,
                'CRAWL_AGENT' =>  $user_agent,
                'PROXY' => $crawlData['USE_PROXY'], 
            ], 501);
        }
    }
}
