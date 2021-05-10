<?php

namespace App\Repositories;

use App\Repositories\Crawlers\Crawlable;
use App\Repositories\Crawlers\CrawlerFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CrawlRepository
{
    public function crawl(&$crawlingData, $alertRevision)
    {
        $excludeCrawler = [];
        if(isset($crawlingData['crawler']) && !empty($crawlingData['crawler'])){
            $crawlerClass = $crawlingData['crawler'];
            $excludeCrawler[] = $crawlerClass;
        }
        $campCrawler = $crawlingData['keyword']->campaign->crawler;
        $includeCrawler = (isset($campCrawler) && $campCrawler == 'random') ? [] : explode(",", $campCrawler);
        $crawler = CrawlerFactory::getCrawler($excludeCrawler, $includeCrawler);
        $crawlingData['crawler'] = get_class($crawler);
        try{
            $response = $crawler->crawl($crawlingData['keyword'], $alertRevision);
            return $response;
        } catch(\Exception $e) {
            Log::error($e->getMessage(), [
                'Crawl Error',
            ]);
            return false;
        }
    }
}
