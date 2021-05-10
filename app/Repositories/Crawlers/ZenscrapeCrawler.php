<?php


namespace App\Repositories\Crawlers;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AlertRevision;

class ZenscrapeCrawler extends BaseCrawler
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('api.crawler.apis.zenscrape.key');
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
        $api_url = "https://app.zenscrape.com/api/v1/get";

        try{
            $response = Http::withHeaders([
                'User-Agent' => $user_agent,
            ])->withOptions([
                'debug' => false,
                'version' => '1.0',
            ])->timeout(config('api.crawler.timeout'))->get($api_url, [
                'apikey' => $this->apiKey,
                'url' => $crawlUrl,
                'keep_headers' => config('api.crawler.header'),
            ]);
            //Log::debug($response->body(), ['ZenscrapeURL', $crawlUrl]);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'CODE' => 'CRAWL_FAILURE_DETECTED',
                'MESSAGE' => $e->getMessage(),
                'CRAWLER' => 'ZenscrapeCrawler',
                'CRAWL_URL'  => $crawlUrl,
                'CRAWL_AGENT' =>  $user_agent,
                'PROXY' => $crawlData['USE_PROXY'],
            ], 501);
        }
    }
}
