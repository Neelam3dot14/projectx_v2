<?php

namespace App\Http\Resources\Scraper\ScraperResponse;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrganicResourceCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Scraper\ScraperResponse\OrganicResource';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
