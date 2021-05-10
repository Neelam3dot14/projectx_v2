<?php

namespace App\Repositories\Scraper;

class ScraperFactory
{
    public static function getScraper()
    {
        $crawlerClass = self::availableScrapers();
        $object = new $crawlerClass;
        return $object;
    }

    public static function availableScrapers()
    {
        $data = [
            NodeScraper::class,
        ];
        return $data[array_rand( $data,1)];
    }
}
