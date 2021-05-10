<?php

namespace App\Events;

use App\Models\Internal\CampaignKeyword;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Internal\AlertRevision;

class KeywordEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const KEYWORD_CREATED = 'keyword.created';
    const KEYWORD_UPDATED = 'keyword.updated';
    const KEYWORD_DELETED = 'keyword.deleted';
    const KEYWORD_CRAWLED = 'keyword.crawled';
    const KEYWORD_SCRAPED = 'keyword.scraped';
    const KEYWORD_TRACED = 'keyword.traced';

    public $keyword, $alert_id;

    /**
     * Create a new event instance.
     *
     * @param CampaignKeyword $keyword
     */
    public function __construct(CampaignKeyword $keyword, $alert_id = null)
    {
        $this->keyword = $keyword;
        if($alert_id){
            $this->alert_id = AlertRevision::find($alert_id);
        }
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
