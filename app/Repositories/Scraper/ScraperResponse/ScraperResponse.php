<?php
namespace App\Repositories\Scraper\ScraperResponse;


class ScraperResponse
{
    public $keyword, $adResults, $metaResults, $organicResults, $shoppingResults;

    public function nodeScraperFormatter($nodeResponse)
    {
        $organicResults = new OrganicResults();
        $adResults = new AdResults();
        $metaResults = new MetaResults();
        $shoppingResults = new ShoppingResults();

        $this->keyword = 'Keyword';
        $this->organicResults = $organicResults;
        $this->adResults = $adResults;
        $this->shoppingResults = $shoppingResults;
        $this->metaResults = $metaResults;

        dd($this);
        return $this;
    }
}
