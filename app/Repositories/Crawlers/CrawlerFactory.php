<?php


namespace App\Repositories\Crawlers;


class CrawlerFactory
{
    public static function getCrawler($excludeCrawler = [], $includeCrawler = [])
    {
        $crawlerClass = self::availableCrawlers($excludeCrawler, $includeCrawler);
        $object = new $crawlerClass;
        return $object;
    }

    public static function availableCrawlers($excludeCrawlers = [], $includeCrawler = [])
    {
        $data = self::generateCrawlersInstances($includeCrawler);
        $availableCrawler[] = $data[array_rand( $data,1)];
        $result = array_diff($availableCrawler, $excludeCrawlers);
        if(empty($result)){
            return $data[array_rand( $data,1)];
        }
        return $result[0];
    }

    public static function generateCrawlersInstances($includeCrawler = [])
    {
        $availableCrawlers = config('api.crawler.apis');
        if(isset($includeCrawler) && !empty($includeCrawler)){
            $crawlerArray = [];
            foreach ($includeCrawler as $key){
                if($availableCrawlers[$key]['active']){
                    $crawlerArray += [ $key => $availableCrawlers[$key] ];
                } 
            }
            if(!empty($crawlerArray)){
                $availableCrawlers = $crawlerArray;
            }
        } 
        $activeCrawlers = [];
        foreach($availableCrawlers as $nickname => $crawler){
            if($crawler['active'] == true){
                for($i=0; $i < $crawler['instances']; $i++){
                    $activeCrawlers[] = self::crawlerResolve($nickname);
                }
            }
        }
        return array_filter($activeCrawlers);
    }

    public static function crawlerResolve($nickname)
    {
        $data = [
            'scraperapi' => ScraperApiCrawler::class,
            'scrapedog' => ScrapeDogCrawler::class,
            'proxy_crawl' => ProxyApiCrawler::class,
            'webscraping_ai' => WebScrapingCrawler::class,
            'scrapingbee' => ScrapingBeeCrawler::class,
            'zenscrape' => ZenscrapeCrawler::class,
        ];
        return ( isset($data[$nickname]) )?$data[$nickname]:null;
    }
}
