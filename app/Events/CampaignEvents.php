<?php

namespace App\Events;

use App\Models\Internal\keywordGroup;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const CAMPAIGN_CREATED = 'campaign.created';
    const CAMPAIGN_UPDATED = 'campaign.updated';
    const CAMPAIGN_DELETED = 'campaign.deleted';

    public $keywordGroup;

    /**
     * Create a new event instance.
     *
     * @param KeywordGroup $keywordGroup
     */
    public function __construct(KeywordGroup $keywordGroup)
    {
        $this->keywordGroup = $keywordGroup;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
