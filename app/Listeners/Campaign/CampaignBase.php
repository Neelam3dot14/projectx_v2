<?php

namespace App\Listeners\Campaign;

use App\Events\CampaignEvents;
use App\Events\CampaignExportEvents;
use App\Events\CampaignKeywordGroupEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CampaignBase
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            CampaignKeywordGroupEvents::CAMPAIGN_KEYWORD_GROUP_CREATED,
            'App\Listeners\Campaign\CampaignKeywordGroupListener@createKeywordGroup'
        );

        $events->listen(
            CampaignEvents::CAMPAIGN_CREATED,
            'App\Listeners\Campaign\CampaignListener@createCampaign'
        );

        $events->listen(
            CampaignExportEvents::CAMPAIGN_EXPORT,
            'App\Listeners\Campaign\CampaignExportListener@exportCampaign'
        );

        $events->listen(
            CampaignExportEvents::CAMPAIGN_EXPORT_ALL,
            'App\Listeners\Campaign\CampaignExportListener@exportAll'
        );
        
        /*$events->listen(
            SourceAPIEvents::PROJECT_CANCELLED,
            'App\Listeners\Internal\Project\SourceAPI\Fulcrum\Project\ProjectCancelled@cancelProject'
        );*/
    }
}
