<?php


namespace App\Listeners\Keyword;

use App\Events\KeywordEvents;
use App\Models\Keyword\KeywordAd;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class saveKeywordAdsListener implements ShouldQueue
{
    public $keyword;

    public function saveKeywordAds(KeywordEvents $keywordEvents)
    {
        $this->keyword = $keywordEvents->keyword;
        if(empty($this->keyword->response_json)){
            return false;
        }
        $response = json_decode($this->keyword->response_json, true);
        $adResults = $response['ad_results'];
        $adData = [];
        foreach ($adResults as $adItem) {
            $item = [
                'keyword_id' => $this->keyword->id,
                'ad_position' => $adItem['position'],
                'ad_link' => $adItem['tracking_link'],
                'ad_text' => $adItem['snippet'],
                'ad_status' => 'PENDING',
            ];
            KeywordAd::create($item);
            if(!empty($adItem['sub_links'])){
                foreach ($adItem['sub_links'] as $subitem){
                    $item = [
                        'keyword_id' => $this->keyword->id,
                        'ad_position' => $adItem['position'],
                        'ad_link' => $subitem['tracking_link'],
                        'ad_text' => $subitem['title'],
                        'ad_status' => 'PENDING',
                    ];
                    KeywordAd::create($item);
                }
            }
        }

    }

}
