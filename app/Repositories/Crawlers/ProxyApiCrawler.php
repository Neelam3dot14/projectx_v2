<?php


namespace App\Repositories\Crawlers;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProxyApiCrawler extends BaseCrawler
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('api.crawler.apis.proxy_crawl.key');
    }

    public function crawl($dataArray, $alertRevision)
    {
        $crawlerArray = [
            'crawl_url' => $this->buildCrawlerUrl($dataArray, $alertRevision),
            'USE_PROXY' => false,
            //'user_agent' => $this->getUserAgent($alertRevision),
        ];
        $response = $this->executeCrawlApi($crawlerArray);
        return ['response'=> $response, 'crawlerArray' => $crawlerArray ];
    }

    public function executeCrawlApi($crawlData)
    {
        $crawlUrl = $crawlData['crawl_url'];
        //$user_agent = $crawlData['user_agent'];
        $api_url = "https://api.proxycrawl.com";
        try{
            $response = Http::withHeaders([
                //'User-Agent' => $user_agent,
                //'version' => '1.0',
            ])->withOptions([
                'debug' => false,
                'version' => '1.0',
                //'Expect' => ''
            ])->timeout(config('api.crawler.timeout'))->get($api_url, [
                'token' => $this->apiKey,  //test api key 'BV_tSYaY9Ao3OWQBknsiNw'
                'url' => $crawlUrl,
            ]);
            //Log::debug($response->body(), ['ProxyApiURL', $crawlUrl]);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'CODE' => 'CRAWL_FAILURE_DETECTED',
                'MESSAGE' => $e->getMessage(),
                'CRAWLER' => "ProxyApiCrawler",
                'CRAWL_URL'  => $crawlUrl,
                //'CRAWL_AGENT' =>  $user_agent,
                'PROXY' => $crawlData['USE_PROXY'], 
            ], 501);
        }
    }
}
