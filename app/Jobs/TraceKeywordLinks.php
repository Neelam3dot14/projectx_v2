<?php

namespace App\Jobs;

use App\Models\Internal\AlertRevision;
use App\Models\Internal\Keyword\KeywordAd;
use App\Repositories\TraceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TraceKeywordLinks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $alertRevision, $tracer;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($alertRevision)
    {
        $this->onQueue('tracer');
        $this->alertRevision = $alertRevision;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(empty($this->alertRevision->scraped_json)){
            return;
        }
        $this->tracer =  new TraceRepository();
        $ads = KeywordAd::where('alert_id', $this->alertRevision->id)
                ->where('ad_status', '=', 'PENDING')
                ->where('ad_type', 'main')
                ->get();
        foreach($ads as $ad){
            $ad->ad_status = 'TRACING';
            $ad->save();
            TraceIndividualAds::dispatch($ad, $this->alertRevision);
        }
        $this->alertRevision->scraping_error = '';
        $this->alertRevision->status = 'TRACED';
        $this->alertRevision->save();
    }
}
