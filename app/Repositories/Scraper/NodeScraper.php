<?php

namespace App\Repositories\Scraper;

use App\Http\Resources\Scraper\ScraperResponseResource;
use App\Models\Internal\AlertRevision;
use App\Models\Scraper\AdLinksResult;
use App\Models\Scraper\AdResult;
use App\Models\Scraper\OrganicResult;
use App\Models\Scraper\ScraperResult;
use App\Repositories\Scraper\ScraperResponse\ScraperResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NodeScraper implements Scrapable
{
    public $keywordData;
    public function scrape(AlertRevision $keywordData)
    {
        $this->keywordData = $keywordData;
        $scrapperArray = [
            'html' => $this->keywordData->getHtml(),
            'keyword' => $this->keywordData->campaignKeyword->keyword,
            'browser' => $this->getBrowser($this->keywordData),
        ];
        $response = $this->executeScrapeApi($scrapperArray);
        if($response->status() == 200){
            $parseResult = $this->parseResponseJson($response->body());
            return ['response'=> $parseResult, 'browser' => $scrapperArray['browser']];
        }
        return ['response'=> $response, 'browser' => $scrapperArray['browser']];
    }

    public function getBrowser(AlertRevision $keywordData)
    {
        $this->keywordData = $keywordData;
        if($this->keywordData->campaignKeyword->device == 'mobile' && $this->keywordData->campaignKeyword->search_engine == 'google'){
            $browser = 'google-mobile';
        } else if($this->keywordData->campaignKeyword->device == 'mobile' && $this->keywordData->campaignKeyword->search_engine == 'bing'){
            $browser = 'bing-mobile';
        } else if($this->keywordData->campaignKeyword->device == 'mobile' && $this->keywordData->campaignKeyword->search_engine == 'yahoo'){
            $browser = 'yahoo-mobile';
        } else{
            $browser = $this->keywordData->campaignKeyword->search_engine;
        }
        return $browser;
    }

    public function executeScrapeApi(Array $scrapeArray)
    {
        $api_url = config('api.node_server.url')."keywords/scrape/";
        try{
            $response = Http::withHeaders([
                'accept-encoding' => 'gzip, deflate',
                'version' => '1.0',
            ])->withOptions([
                'debug' => false,
                'version' => '1.0',
                'Expect' => ''
            ])->timeout(config('api.scraper.timeout'))->post($api_url, $scrapeArray);
            return $response;
        } catch (\Exception $e) {
            return response($e->getMessage(), 501);
        }
    }

    public function parseResponseJson($responseJson)
    {
        if(!$responseJson || !json_decode($responseJson)){
            Log::debug('581 Error', ['response body', $responseJson]);
            return response($responseJson, 581);
        }

        $reformatData = [];
        $data = json_decode($responseJson, true)['results'];
        list($keyword) = array_keys($data);
        if(!isset($data[$keyword][1])){
            Log::info('Debugging', array('response_body' => $responseJson, 'data'=> $data));
            return response($responseJson, 582);
        }
        $results = $data[$keyword][1];
        $reformatData = new ScraperResult([
            'keyword' => $keyword,
            'organic_results' => $this->parseOrganicResults($results['results']),
            'meta_results' => $this->parseMetaResults($results),
            'ad_results' => $this->parseAdResults($results),
        ]);
        $response = new ScraperResponseResource($reformatData);
        return response($response, 200);
    }

    public function parseOrganicResults($result)
    {
        $organic_results = [];
        foreach($result as $index => $item){
            $organic_results[] = new OrganicResult($item) ;
        }
        return collect($organic_results);
    }

    public function parseMetaResults($results)
    {
        return [
            'num_results' => $results['num_results'],
            'effective_query' => $results['effective_query'],
            'time' => $results['time'],
        ];
    }

    public function parseAdResults($results)
    {
        $ads = [];
        if(isset($results['top_ads']) && !empty($topAds = $results['top_ads'])) {
            foreach($topAds as $ad){
                $newAd = [
                    'position' => 'top',
                    'visible_link' => $ad['visible_link'],
                    'tracking_link' => $ad['tracking_link'],
                    'link' => $ad['link'],
                    'title' => $ad['title'],
                    'snippet' => $ad['snippet'],
                    'sub_links' => $this->parseSubAds($ad['links']),
                ];
                $ads[] = new AdResult($newAd);
            }
        }
        if(isset($results['bottom_ads']) && !empty($bottomAds = $results['bottom_ads'])) {
            foreach($bottomAds as $ad){
                $newAd = [
                    'position' => 'bottom',
                    'visible_link' => $ad['visible_link'],
                    'tracking_link' => $ad['tracking_link'],
                    'link' => $ad['link'],
                    'title' => $ad['title'],
                    'snippet' => $ad['snippet'],
                    'sub_links' => $this->parseSubAds($ad['links']),
                ];
                $ads[] = new AdResult($newAd);
            }
        }
        return collect($ads);
    }

    public function parseSubAds($subLinks)
    {
        $adLinks = [];
        if(!empty($subLinks)) {
            foreach($subLinks as $link){
                $newLink = [
                    'tracking_link' => $link['tracking_link'],
                    'link' => $link['link'],
                    'title' => $link['title'],
                    'snippet' => isset($link['snippet']) ? $link['snippet'] : '',
                ];
                $adLinks[] = new AdLinksResult($newLink);
            }
        }
        return collect($adLinks);
    }
}
