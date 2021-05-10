<?php


namespace App\Repositories;


use App\Repositories\Scraper\ScraperFactory;
use Illuminate\Support\Facades\Log;

class ScrapeRepository
{
    public function scrape($keyword)
    {
        $crawler = ScraperFactory::getScraper();
        try{
            $response = $crawler->scrape($keyword);
        } catch(\Exception $e) {
            Log::error($e->getMessage(), [
                'Scrape Error',
            ]);
            return false;
        }
        return $response;
    }
}
