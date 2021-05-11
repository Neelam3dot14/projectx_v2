<?php


namespace App\Repositories;

use App\Events\KeywordEvents;
use App\Models\Internal\Campaign;
use App\Models\Internal\CampaignKeyword;
use App\Models\Internal\Keyword\KeywordAd;
use App\Models\Internal\KeywordGroup;
use App\Models\Internal\AdHijack;
use App\Models\Internal\AdCompetition;
use App\Http\Resources\Keyword\KeywordGroupResource;
use App\Events\CampaignEvents;
use App\Repositories\Geotarget\GeotargetRepository;
use App\Models\Internal\AlertRevision;
use App\Jobs\Internal\AdsCompetition;

class CampaignRepository
{
    protected $geotargetRepo;
    public function getCampaigns()
    {
        $result = Campaign:://where('created_by',\Auth::id())   //where clause for created_by current user only
                    withCount('alertRevisions','adCompetitors', 'adHijacks')->get();
                    //->paginate($paginate);
        return $result;
    }

    public function find($campaignId)
    {
        $result = Campaign::find($campaignId);
        return $result;

    }

    public function view($campaign_id)
    {
        $result = Campaign::where('id', $campaign_id)
            ->withCount(['alertRevisions', 'alertRevisions as success_revisions_count' => function ($query) {
                $query->where('status', '=', 'TRACED');
            }, 'alertRevisions as crawl_failed_revisions_count' => function ($query) {
                $query->where('status', '=', 'CRAWL_FAILED');
            }, 'alertRevisions as scraping_failed_revisions_count' => function ($query) {
                $query->where('status', '=', 'SCRAPING_FAILED');
            }, 'alertRevisions as pending_revisions_count' => function ($query) {
                $query->where('status', '=', 'PENDING');
            }])
            ->get();
        return $result;
    }

    public function executeKeywordsProcessing($campaign_id)
    {
        /*$alertRevision = AlertRevision::find(85);
        $ad = KeywordAd::find(571);
        AdsCompetition::dispatch($alertRevision, $ad);*/
        /*$keyword = CampaignKeyword::find(2);
        event(KeywordEvents::KEYWORD_SCRAPED, new KeywordEvents($keyword, 3));
        //event(KeywordEvents::KEYWORD_CRAWLED, new KeywordEvents($keyword, 1));*/
        $campaignKeywords = Campaign::find($campaign_id)
            ->keywords()->get();
        foreach($campaignKeywords as $keyword){
            event(KeywordEvents::KEYWORD_CREATED, new KeywordEvents($keyword));
        }
    }

    public function pauseCampaign($campaign_id)
    {
        $result = Campaign::where('id', $campaign_id)
                    ->where('created_by',\Auth::id())
                    ->update(['status' => 'INACTIVE']);
        return $result;
    }

    public function activateCampaign($campaign_id)
    {
        $result = Campaign::where('id', $campaign_id)
                    ->where('created_by',\Auth::id())
                    ->update(['status' => 'ACTIVE']);
        return $result;
    }

    public function exportCampaign($campaignId)
    {
        $columns = $this->getExportableColumns();
        $callback = function() use($campaignId, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $this->getKeywordAdsByCampaignId($campaignId, $file);
            fclose($file);
        };
        return $callback;
    }

    public function exportAllCampaign($user_id)
    {
        $columns = $this->getExportableColumns();
        $campaigns = Campaign::where('created_by',\Auth::id())->pluck('id')->all();
        $callback = function() use($campaigns, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $this->getKeywordAdsByCampaignArray($campaigns, $file);
            fclose($file);
        };
        return $callback;
    }

    public function getExportableColumns()
    {
        return [
            'Campaign ID',
            'Keyword ID',
            'Keyword Group ID',
            'Revision ID',
            'Ad Id',
            'Campaign Name',
            'Keyword',
            'Country',
            'States',
            'Ad Location',
            'Device',
            'Ad Timestamp',
            'Search Engine',
            'Ad Title',
            'Ad Position',
            'Ad Text',
            'Ad Display URL',
            'AdHijack',
            'AdCompetition',
            'URL Traces',
            'Html Link',
            'Crawled Metadata',
        ];
    }

    public function generateExportableDataArray($keywordAd)
    {

        $keyword = $keywordAd->keyword;
        $campaign = $keyword->campaign;
        $adTraces = $keywordAd->adTraces;
        $alertRevisions = $keywordAd->alertRevision;
        $ad_hijack  = $this->checkAdHijacks($campaign->id, $keywordAd->id);
        $ad_competition = $this->checkAdCompetitions($campaign->id, $keywordAd->id);
        $ads = '';
        foreach($adTraces as $ad){
            $ads .= $ad->traced_url .' > '. $ad->redirect_type . ' > ';
        }
        $previewUrl = config('app.url') . '/keyword/'. $alertRevisions->id . '/html';

        $data = [
            'Campaign ID' => $campaign->id,
            'Keyword ID' => $keyword->id,
            'Keyword Group ID' => $keywordAd->keyword_group_id,
            'Revision ID' => $alertRevisions->id,
            'Ad Id' => $keywordAd->id,
            'Campaign Name'=> $campaign->name,
            'Keyword' => $keyword->keyword,
            'Country' => $campaign->country,
            'States' => $alertRevisions->canonical_states,
            'Ad Location' => $alertRevisions->canonical_name,
            'Device' => $keyword->device,
            'Ad Timestamp' => $keywordAd->created_at->toDateTimeString(),
            'Search Engine' => $keyword->search_engine,
            'Ad Title' => $keywordAd->ad_title,
            'Ad Position' => $keywordAd->ad_position,
            'Ad Text' => $keywordAd->ad_text,
            'Ad Display URL' => $keywordAd->ad_visible_link,
            'AdHijack' => $ad_hijack,
            'AdCompetition' => $ad_competition,
            'URL Traces' => $ads,
            'Html Link' => $previewUrl,
            'Crawled Metadata' => $alertRevisions->crawler_metadata,
        ];
        return $data;
    }

    public function checkAdHijacks($campaign_id, $ad_id)
    {
        if (AdHijack::where('campaign_id', $campaign_id)->where('ad_id', $ad_id)->exists()) {
            return 'Yes';
        } else{
            return 'No';
        }
    }

    public function checkAdCompetitions($campaign_id, $ad_id)
    {
        if (AdCompetition::where('campaign_id', $campaign_id)->where('keyword_ad_id', $ad_id)->exists()) {
            return 'Yes';
        } else{
            return 'No';
        }
    }

    public function addNewKeyword(Campaign $campaign, $keywords, $data)
    {
        foreach($keywords as $keyword){
            $campKeyword = Campaign::find($campaign->id)
                    ->keywords()->where('keyword', trim($keyword))
                    ->get();
            if(count($campKeyword) == 0){
                $keywordGroupData = [
                    'campaign_id' => $campaign->id,
                    'keyword' => trim($keyword),
                    'device' => $data['device'],
                    'search_engine' => $data['search_engine'],
                    'country_code' => $data['country'],
                    'states' => $data['canonical_states'],
                    'location' => $data['location'],
                ];
                $keywordGroup = KeywordGroup::create($keywordGroupData);
                event(CampaignEvents::CAMPAIGN_CREATED, new CampaignEvents($keywordGroup));
            }
        }
    }

    public function removeKeyword(Campaign $campaign, $keywords)
    {
        foreach($keywords as $keyword){
            $result = Campaign::find($campaign->id)
                ->keywords()->where('keyword', $keyword)
                ->get();
            foreach ($result as $res) {
                $res->delete();
            }
        }
    }

    public function updateMatchKeywords(Campaign $campaign, $keywords, $data)
    {
        foreach($keywords as $keyword){
            $result = Campaign::find($campaign->id)->keywordGroups()
                ->where('keyword', $keyword)
                ->get();
            foreach ($result as $res) {
                //For Device
                $old_device  = explode(',', $res->device);
                $new_device =  explode(',', $data['device']);
                $added_device = array_diff($new_device, $old_device);
                $removed_device = array_diff($old_device, $new_device);
                if(!empty($added_device)){
                    $this->addNewDevice($campaign, $added_device, $res, $data);
                }
                if(!empty($removed_device)){
                    $this->removeDevice($campaign, $removed_device, $keyword);
                }

                //For Search Engine
                $old_search_engine  = explode(',', $res->search_engine);
                $new_search_engine =  explode(',', $data['search_engine']);
                $added_search_engine = array_diff($new_search_engine, $old_search_engine);
                $removed_search_engine = array_diff($old_device, $new_search_engine);
                if(!empty($added_search_engine)){
                    $this->addNewSearchEngine($campaign, $added_search_engine, $res, $data);
                }
                if(!empty($removed_search_engine)){
                    $this->removeSearchEngine($campaign, $removed_search_engine, $keyword);
                }

                //For Country
                $old_country  = explode(',', $res->country_code);
                $new_country =  explode(',', $data['country']);
                $added_country = array_diff($new_country, $old_country);
                $removed_country = array_diff($old_device, $new_country);
                if(!empty($added_country)){
                    $this->addNewCountry($campaign, $added_country, $res, $data);
                }
                if(!empty($removed_country)){
                    $this->removeCountry($campaign, $removed_country, $keyword);
                }
            }
            $deviceUpdate = [
                'device' => implode(',', $new_device),
                'search_engine' => strtolower(implode(',', $new_search_engine)),
                'country_code' => implode(',', $new_country),
                'states' =>  $data['canonical_states'],
                'location' => $data['location'],
            ];
            $res->update($deviceUpdate);
        }
    }

    public function removeDevice(Campaign $campaign, $removed_device, $keyword)
    {
        foreach($removed_device as $device){
            $result = Campaign::find($campaign->id)
                ->keywords()->where('keyword', $keyword)
                ->where('device', $device)
                ->get();
            foreach ($result as $res) {
                $res->delete();
            }
        }
    }

    public function addNewDevice(Campaign $campaign, $added_device, $keywordGp, $data)
    {
        foreach($added_device as $device){
            $keywordGroup = Campaign::find($campaign->id)
                            ->keywords()->where('keyword', trim($keywordGp->keyword))
                            ->where('device', $device)
                            ->get();
            if(count($keywordGroup) == 0){
                $this->geotargetRepo = new GeotargetRepository();
                $locations = json_decode($data['location'], true);
                $searchEngines = explode(',', $data['search_engine']);
                foreach($locations as $locationInfo){
                    foreach($searchEngines as $searchEngine){
                        $datetime = NULL;
                        if($searchEngine == 'yahoo'){
                            $result = $this->geotargetRepo->checkYahooDomainByCountryCode(strtoupper($locationInfo['country_code']));
                            if(!$result){
                                $datetime = date('Y-m-d h:i:s');
                            }
                        }   
                        $language = $this->geotargetRepo->getLanguage(strtoupper($locationInfo['country_code']));
                        $keywordData = [
                            'campaign_id' => $campaign->id,
                            'keyword_group_id' => $keywordGp->id,
                            'keyword' => $keywordGp->keyword,
                            'proxy_use' => 'default',
                            'device' => strtolower($device),
                            'search_engine' => strtolower($searchEngine),
                            'country_code' => strtolower($locationInfo['country_code']),
                            'lang' => $language,
                            'google_domain' => $locationInfo['google_domain'],
                            'deleted_at' => $datetime,
                        ];
                        $campaignKeyword = CampaignKeyword::create($keywordData);
                    }
                }
            }
        }
    }

    public function removeSearchEngine(Campaign $campaign, $removed_search_engine, $keyword)
    {
        foreach($removed_search_engine as $search_engine){
            $result = Campaign::find($campaign->id)
                ->keywords()->where('keyword', $keyword)
                ->where('search_engine', $search_engine)
                ->get();
            foreach ($result as $res) {
                $res->delete();
            }
        }
    }

    public function addNewSearchEngine(Campaign $campaign, $added_search_engine, $keywordGp, $data)
    {
        foreach($added_search_engine as $searchEngine){
            $keywordGroup = Campaign::find($campaign->id)
                        ->keywords()->where('keyword', trim($keywordGp->keyword))
                        ->where('search_engine', $searchEngine)
                        ->get();
            if(count($keywordGroup) == 0){
                $this->geotargetRepo = new GeotargetRepository();
                $locations = json_decode($data['location'], true);
                $devices = explode(',', $data['device']);
                foreach($locations as $locationInfo){
                    foreach($devices as $device){
                        $datetime = NULL;
                        if($searchEngine == 'yahoo'){
                            $result = $this->geotargetRepo->checkYahooDomainByCountryCode(strtoupper($locationInfo['country_code']));
                            if(!$result){
                                $datetime = date('Y-m-d h:i:s');
                            }
                        }
                        $language = $this->geotargetRepo->getLanguage(strtoupper($locationInfo['country_code']));
                        $keywordData = [
                            'campaign_id' => $campaign->id,
                            'keyword_group_id' => $keywordGp->id,
                            'keyword' => $keywordGp->keyword,
                            'proxy_use' => 'default',
                            'device' => strtolower($device),
                            'search_engine' => strtolower($searchEngine),
                            'country_code' => strtolower($locationInfo['country_code']),
                            'lang' => $language,
                            'google_domain' => $locationInfo['google_domain'],
                            'deleted_at' => $datetime,
                        ];
                        $campaignKeyword = CampaignKeyword::create($keywordData);
                    }
                }
            }
        }
    }

    public function removeCountry(Campaign $campaign, $removed_country, $keyword)
    {
        foreach($removed_country as $country){
            $result = Campaign::find($campaign->id)
                ->keywords()->where('keyword', $keyword)
                ->where('country_code', $country)
                ->get();
            foreach ($result as $res) {
                $res->delete();
            }
        }
    }

    public function addNewCountry(Campaign $campaign, $added_country, $keywordGp, $data)
    {
        foreach($added_country as $country){
            $keywordGroup = Campaign::find($campaign->id)
                        ->keywords()->where('keyword', trim($keywordGp->keyword))
                        ->where('country_code', $country)
                        ->get();
            if(count($keywordGroup) == 0){
                $this->geotargetRepo = new GeotargetRepository();
                $devices =  explode(',', $data['device']);
                $searchEngines = explode(',', $data['search_engine']);
                foreach($devices as $device){
                    foreach($searchEngines as $searchEngine){
                        $datetime = NULL;
                        if($searchEngine == 'yahoo'){
                            $result = $this->geotargetRepo->checkYahooDomainByCountryCode(strtoupper($country));
                            if(!$result){
                                $datetime = date('Y-m-d h:i:s');
                            }
                        }
                        $language = $this->geotargetRepo->getLanguage(strtoupper($country));
                        $keywordData = [
                            'campaign_id' => $campaign->id,
                            'keyword_group_id' => $keywordGp->id,
                            'keyword' => $keywordGp->keyword,
                            'proxy_use' => 'default',
                            'device' => strtolower($device),
                            'search_engine' => strtolower($searchEngine),
                            'country_code' => strtolower($country),
                            'lang' => $language,
                            'google_domain' => $this->geotargetRepo->getGoogleDomain(strtoupper($country)),
                            'deleted_at' => $datetime,
                        ];
                        $campaignKeyword = CampaignKeyword::create($keywordData);
                    }
                }
            }
        }
    }
    
    public function getKeywordAdsByCampaignId($campaignId, &$file)
    {
        KeywordAd::where('ad_type', 'main')->whereHas('keyword', function($q) use ($campaignId) {
            $q->where('campaign_id', '=', $campaignId);
        })->with('adTraces', 'alertRevision', 'keyword', 'keyword.campaign','adCompetitors', 'adHijacks')
            ->chunk(100, function($keywordAds) use ($file) {
                $keywordAds->each(function($keywordAd) use ($file) {
                    $data = $this->generateExportableDataArray($keywordAd);
                    fputcsv($file, $data);
                });
            });
    }

    public function getKeywordAdsByCampaignArray($campaignIdArr, &$file)
    {
        KeywordAd::where('ad_type', 'main')->whereHas('keyword', function($q) use ($campaignIdArr) {
            $q->whereIn('campaign_id', $campaignIdArr);
        })->with('adTraces', 'alertRevision',  'keyword', 'keyword.campaign')
            ->chunk(200, function($keywordAds) use ($file) {
            $keywordAds->each(function($keywordAd) use ($file) {
                $data = $this->generateExportableDataArray($keywordAd);
                fputcsv($file, $data);
            });
        });
    }

    public function getKeywordGroup($campaign_id)
    {
        $keywordGroup = KeywordGroup::where('campaign_id', $campaign_id)
            ->withCount(['keywordGroupAd', 'keywordGroupAd as keyword_main_ads_count' => function ($query) {
                $query->where('ad_type', '=', 'main');
            }])
            ->get();
        return KeywordGroupResource::collection($keywordGroup);
    }
}
