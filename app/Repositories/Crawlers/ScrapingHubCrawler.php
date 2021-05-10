<?php


namespace App\Repositories\Crawlers;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class ScrapingHubCrawler extends BaseCrawler
{
    public function crawl($dataArray)
    {
        $crawlerArray = [
            'crawl_url' => $this->buildCrawlerUrl($dataArray),
            'USE_PROXY' => true,
            'PROXY_DATA' => [
                'PROXY_SCHEME' => 'PROXY_SERVER:PROXY_PORT',
                'PROXY_SERVER' => 'proxy.crawlera.com',
                'PROXY_PORT' => 8010,
                'AUTHENTICATION' => 'BEARER_TOKEN',
                'TOKEN' => 'c9c6bd34281e40b5935c3ba9e77f9279',
                'CUSTOM_HEADERS' => [
                    "x-crawlera-use-https:1",
                ],
            ],


        ];
        $response = $this->executeCrawlApi($crawlerArray);
        return $response;
    }

    public function executeCrawlApi($crawlData)
    {
        $crawlUrl = $crawlData['crawl_url'];
        $ch = curl_init();
        $proxy_host = $crawlData['PROXY_DATA']['PROXY_SERVER'];
        $proxy_port = $crawlData['PROXY_DATA']['PROXY_PORT'];
        $proxy_user = $crawlData['PROXY_DATA']['TOKEN'];
        $proxy_pass = '';
        $proxy_url = "http://{$proxy_user}:{$proxy_pass}@{$proxy_host}:{$proxy_port}";

        $url = $crawlUrl;
        $guzzle_client = new Client([
            'verify' => false,
        ]);
        $result = $guzzle_client->request('GET', $url, [
            'proxy' => $proxy_url,
            'headers' => [
                //'X-Crawlera-Cookies' => 'disable',
                'Accept-Encoding' => 'gzip, deflate, br',
            ]
        ]);

        if($result->getStatusCode() == 200){
            return $result->getBody()->getContents();
        }
        return false;
    }
}
