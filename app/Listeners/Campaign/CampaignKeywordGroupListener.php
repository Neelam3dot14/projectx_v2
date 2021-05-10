<?php


namespace App\Listeners\Campaign;


use App\Events\CampaignKeywordGroupEvents;
use App\Events\CampaignEvents;
use App\Models\Internal\KeywordGroup;
use Illuminate\Contracts\Queue\ShouldQueue;

class CampaignKeywordGroupListener implements ShouldQueue
{
    public $campaign;

    public function createKeywordGroup(CampaignKeywordGroupEvents $campaignKeywordGroupEvents)
    {
        $this->campaign = $campaignKeywordGroupEvents->campaign;
        $keywords = explode(',', $this->campaign->keywords);
        foreach($keywords as $keyword) {
            $keywordGroupData = [
                'campaign_id' => $this->campaign->id,
                'keyword' => trim($keyword),
                'device' => $this->campaign->device,
                'search_engine' => $this->campaign->search_engine,
                'country_code' => $this->campaign->country,
                'states' => $this->campaign->canonical_states,
                'location' => $this->campaign->location,
            ];
            $keywordGroup = KeywordGroup::create($keywordGroupData);
            event(CampaignEvents::CAMPAIGN_CREATED, new CampaignEvents($keywordGroup));
        }
        return false;
    }
}
