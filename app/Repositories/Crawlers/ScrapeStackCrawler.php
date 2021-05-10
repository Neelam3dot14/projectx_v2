<?php


namespace App\Repositories\Crawlers;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapeStackCrawler extends BaseCrawler
{
    private $apiKey='fb8d782092f0b8207558df41123a1cab'; //test access key'88fd2c6b8c50a009ddf6a7b721178175'
    public function crawl($dataArray)
    {
        $crawlerArray = [
            'crawl_url' => $this->buildCrawlerUrl($dataArray),
            'USE_PROXY' => false,
            'user_agent' => $this->getRandomUserAgent($dataArray->device),
        ];
        $response = $this->executeCrawlApi($crawlerArray);
        return $response;
    }

    public function executeCrawlApi($crawlData)
    {
        $crawlUrl = $crawlData['crawl_url'];
        $user_agent = $crawlData['user_agent'];
        $api_url = "http://api.scrapestack.com/scrape";
        try{
            $response = Http::withHeaders([
                'User-Agent' => $user_agent,
                //'version' => '1.0',
            ])->withOptions([
                'debug' => false,
                'version' => '1.0',
            ])->timeout(config('api.crawler.timeout'))->get($api_url, [
                'access_key' => $this->apiKey,
                'url' => $crawlUrl,
                'keep_headers' => (int)config('api.crawler.header'),
            ]);
            return $response;

        } catch (\Exception $e) {
            return response()->json([
                "CODE"=> "CRAWL_FAILURE_DETECTED",
                "MESSAGE"=> $e->getMessage(),
                'CRAWLER' => "ScrapeStackCrawler",
            ], 501);
        }
    }
}
