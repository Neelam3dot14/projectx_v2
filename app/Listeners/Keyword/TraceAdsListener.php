<?php


namespace App\Listeners\Keyword;

use App\Events\KeywordEvents;
use App\Jobs\TraceKeywordLinks;
use App\Repositories\TraceRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class TraceAdsListener implements ShouldQueue
{
    public $alertRevision;

    public function traceAds(KeywordEvents $keywordEvents)
    {
        $this->alertRevision = $keywordEvents->alert_id;
        $this->alertRevision->status = 'TRACING';
        $this->alertRevision->save();
        TraceKeywordLinks::dispatch($this->alertRevision);
    }
}
