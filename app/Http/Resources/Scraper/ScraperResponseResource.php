<?php

namespace App\Http\Resources\Scraper;

use App\Http\Resources\Scraper\ScraperResponse\AdCollectionResource;
use App\Http\Resources\Scraper\ScraperResponse\MetaResource;
use App\Http\Resources\Scraper\ScraperResponse\OrganicResource;
use App\Http\Resources\Scraper\ScraperResponse\OrganicResourceCollection;
use App\Models\Scraper\MetaResult;
use App\Models\Scraper\OrganicResult;
use Illuminate\Http\Resources\Json\JsonResource;

class ScraperResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'keyword' => $this->keyword,
            'meta_results' => new MetaResource(new MetaResult($this->meta_results)),
            'organic_results' => new OrganicResourceCollection( $this->organic_results ),
            'ad_results' => new AdCollectionResource( $this->ad_results ),
        ];
        //return parent::toArray($request);
    }
}
