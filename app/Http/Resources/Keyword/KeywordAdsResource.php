<?php

namespace App\Http\Resources\Keyword;

use Illuminate\Http\Resources\Json\JsonResource;

class KeywordAdsResource extends JsonResource
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
            'keyword_id' => $this->keyword_id,
            'keyword_group_id' => $this->keyword_group_id,
            'device' => $this->device,
            'search_engine' => $this->search_engine,
            'ad_title' => $this->ad_title,
            'ad_type' => $this->ad_type,
            'ad_visible_link' => $this->ad_visible_link,
            'ad_count' => $this->ad_count,
            'ad_position' => $this->ad_position,
            'ad_link' => $this->ad_link,
            'ad_text' => $this->ad_text,
            'ad_status' => $this->ad_status,
            'ad_traces_count' => $this->ad_traces_count,
            'keyword_ads_id' => $this->keyword_ads_id,
            'ad_competitors_count' => $this->ad_competitors_count,
            'ad_hijacks_count' => $this->ad_hijacks_count,
        ];
    }
}
