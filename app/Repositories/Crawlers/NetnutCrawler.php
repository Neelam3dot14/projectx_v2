<?php


namespace App\Repositories\Crawlers;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class NetnutCrawler extends BaseCrawler
{
    private $username='3dot14';
    private $password='1qPWpk7GgQPi';
    private $keyword;
    public function crawl($dataArray)
    {
        $this->keyword = $dataArray;
        $crawlerArray = [
            'crawl_url' => $this->buildCrawlerUrl($dataArray),
            'USE_PROXY' => true,
            'PROXY_DATA' => [
                'PROXY_SCHEME' => 'PROXY_SERVER:PROXY_PORT',
                'PROXY_SERVER' => 'gw.ntnt.io',
                'PROXY_PORT' => 5959,
                'AUTHENTICATION' => 'BASIC_AUTH',
                'PROXY_USERNAME' => "$this->username".'-cc-'.strtoupper($this->keyword->country_code),
                'PROXY_PASSWORD' => $this->password,
            ],
        ];
        $response = $this->executeCrawlApi($crawlerArray);
        return $response;
    }

    public function executeCrawlApi($crawlData)
    {
        $api_url = config('api.node_server.url')."keywords/crawl/";

        $proxy_host = $crawlData['PROXY_DATA']['PROXY_SERVER'];
        $proxy_port = $crawlData['PROXY_DATA']['PROXY_PORT'];
        $proxy_user = $crawlData['PROXY_DATA']['PROXY_USERNAME'];
        $proxy_pass = $crawlData['PROXY_DATA']['PROXY_PASSWORD'];
        $proxy_url = "http://{$proxy_user}:{$proxy_pass}@{$proxy_host}:{$proxy_port}";
        try{
            dd($crawlData);
            $response = Http::withHeaders([
                'accept-encoding' => 'gzip, deflate',
                'version' => '1.0',
            ])->withOptions([
                'debug' => false,
                'version' => '1.0',
                'Expect' => ''
            ])->timeout(config('api.crawler.timeout'))->get($api_url, [
                'url' => $crawlData['crawl_url'],
                'USE_PROXY' => $crawlData['USE_PROXY'],
                'PROXY_DATA' => $crawlData['PROXY_DATA']
            ]);
            dd($response);

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                "CODE"=> "CRAWL_FAILURE_DETECTED",
                "MESSAGE"=> $e->getMessage(),
                'CRAWLER' => "NetNut Crawler",
            ], 500);
        }
    }
}
