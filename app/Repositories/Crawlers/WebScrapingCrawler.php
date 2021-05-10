<?php


namespace App\Repositories\Crawlers;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebScrapingCrawler extends BaseCrawler
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('api.crawler.apis.webscraping_ai.key');
    }

    public function crawl($dataArray, $alertRevision)
    {
        $crawlerArray = [
            'crawl_url' => $this->buildCrawlerUrl($dataArray, $alertRevision),
            'USE_PROXY' => false,
            'user_agent' => $this->getUserAgent($alertRevision),
            'device' => $alertRevision->campaignKeyword->device,
        ];
        $response = $this->executeCrawlApi($crawlerArray);
        return ['response'=> $response, 'crawlerArray' => $crawlerArray ];
    }

    public function executeCrawlApi($crawlData)
    {
        $crawlUrl = $crawlData['crawl_url'];
        $user_agent = $crawlData['user_agent'];
        $api_url = "https://api.webscraping.ai/html";
        try{
            $response = Http::withHeaders([
                //'User-Agent' => $user_agent,
            ])->timeout(config('api.crawler.timeout'))->get($api_url, [
                'api_key' => $this->apiKey,
                'js' => true,        //!config('api.crawler.header'),
                'url' => $crawlUrl,
                'device' => $crawlData['device'],
                'headers[User-Agent]' => $user_agent
            ]);
            //Log::debug($response->body(), ['WebScrapingURL', $crawlUrl]);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'CODE' => 'CRAWL_FAILURE_DETECTED',
                'MESSAGE' => $e->getMessage(),
                'CRAWLER' => 'WebScrapingAICrawler',
                'CRAWL_URL'  => $crawlUrl,
                'CRAWL_AGENT' =>  $user_agent,
                'PROXY' => $crawlData['USE_PROXY'], 
            ], 501);
        }
    }
}
