<?php


namespace App\Repositories\Crawlers;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapingBeeCrawler extends BaseCrawler
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('api.crawler.apis.scrapingbee.key');
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
        $find_google = strpos($crawlUrl, 'http://www.google');
        $custom_google = ($find_google !== false) ? true : false;
        $user_agent = $crawlData['user_agent'];
        $api_url = "https://app.scrapingbee.com/api/v1/";
        
        try{
            $response = Http::withHeaders([
                'Spb-User-Agent' => $user_agent,
            ])->withOptions([
                'debug' => false,
                'version' => '1.0',
            ])->timeout(config('api.crawler.timeout'))->get($api_url, [
                'api_key' => $this->apiKey,
                'url' => $crawlUrl,
                'forward_headers' => config('api.crawler.header'),
                'custom_google' => $custom_google,
            ]);
            //Log::debug($response->body(), ['ScrapingBeeURL', $crawlUrl]);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'CODE' => 'CRAWL_FAILURE_DETECTED',
                'MESSAGE' => $e->getMessage(),
                'CRAWLER' => 'ScrapingBeeCrawler',
                'CRAWL_URL'  => $crawlUrl,
                'CRAWL_AGENT' =>  $user_agent,
                'PROXY' => $crawlData['USE_PROXY'],
            ], 501);
        }
    }
}
