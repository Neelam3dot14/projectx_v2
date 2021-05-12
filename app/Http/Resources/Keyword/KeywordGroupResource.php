<?php

namespace App\Http\Resources\Keyword;

use Illuminate\Http\Resources\Json\JsonResource;

class KeywordGroupResource extends JsonResource
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
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'keyword' => $this->keyword,
            'device' => $this->device,
            'search_engine' => $this->search_engine,
            'country_code' => $this->country_code,
            'states' => $this->states,
            'location' => $this->location,
            //'keyword_ads_count' => $this->keyword_group_ad_count, //Total keyword Ads Count (main & sub both)
            'keyword_main_ads_count' => $this->keyword_main_ads_count,
        ];

    }
}
