<?php

namespace App\Http\Resources\Scraper\ScraperResponse;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganicResource extends JsonResource
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
            'title' => $this->title,
            'snippet' => $this->snippet,
            'visible_link' => $this->visible_link,
            'date' => $this->date,
            'rank' => $this->rank,
        ];
        //return parent::toArray($request);
    }
}
