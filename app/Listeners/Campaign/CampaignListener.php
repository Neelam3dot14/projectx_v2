<?php


namespace App\Listeners\Campaign;

use App\Events\CampaignEvents;
use App\Events\KeywordEvents;
use App\Models\Internal\CampaignKeyword;
use App\Repositories\Geotarget\GeotargetRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class CampaignListener implements ShouldQueue
{
    public $keywordGroup, $geotargetRepo;

    public function createCampaign(CampaignEvents $campaignEvents)
    {
        $this->geotargetRepo = new GeotargetRepository();
        $this->keywordGroup = $campaignEvents->keywordGroup;
        $devices = explode(',', $this->keywordGroup->device);
        $locations = json_decode($this->keywordGroup->location, true);
        $searchEngines = explode(',', $this->keywordGroup->search_engine);
        foreach($locations as $locationInfo){
            foreach($devices as $device){
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
                        'campaign_id' => $this->keywordGroup->campaign_id,
                        'keyword_group_id' => $this->keywordGroup->id,
                        'keyword' => $this->keywordGroup->keyword,
                        'proxy_use' => 'default',
                        'device' => strtolower($device),
                        'search_engine' => $searchEngine,
                        'country_code' => strtolower($locationInfo['country_code']),
                        'lang' => $language,
                        'google_domain' => $locationInfo['google_domain'],
                        'deleted_at' => $datetime,
                    ];
                    $campaignKeyword = CampaignKeyword::create($keywordData);
                }
            }
        }
        return false;
    }
}

