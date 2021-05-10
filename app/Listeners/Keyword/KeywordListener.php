<?php


namespace App\Listeners\Keyword;


use App\Events\KeywordEvents;
use App\Jobs\CrawlKeyword;
use App\Jobs\ScrapeKeyword;
use App\Jobs\SerpKeyword;
use App\Jobs\TraceKeywordLinks;
use App\Models\CampaignKeyword;
use App\Models\Keyword\KeywordAd;
use App\Repositories\CrawlRepository;
use App\Repositories\ScrapeRepository;
use App\Repositories\SerpRepository;
use App\Repositories\TraceRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class KeywordListener implements ShouldQueue
{
    public $keyword, $crawler, $serper, $scraper, $tracer;

    public function createKeyword(KeywordEvents $keywordEvents)
    {
        $this->keyword = $keywordEvents->keyword;

        if($this->keyword->campaign->execution_type == 'Crawl'){
            CrawlKeyword::dispatch($this->keyword);
        }
        if($this->keyword->Campaign->type == 'SERP'){
            SerpKeyword::dispatch($this->keyword);
        }
        return false;
    }

    public function scrapeKeyword(KeywordEvents $keywordEvents)
    {
        $this->keyword = $keywordEvents->keyword;
        ScrapeKeyword::dispatch($this->keyword);
    }

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

    public function traceAds(KeywordEvents $keywordEvents)
    {
        $this->keyword = $keywordEvents->keyword;
        TraceKeywordLinks::dispatch($this->keyword);

    }
}
