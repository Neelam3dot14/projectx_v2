<?php

namespace App\Http\Resources\Scraper\ScraperResponse;

use Illuminate\Http\Resources\Json\JsonResource;

class MetaResource extends JsonResource
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
            'num_results' => $this->num_results,
            'effective_query' => $this->effective_query,
            'time' => $this->time,
        ];
        //return parent::toArray($request);
    }
}
