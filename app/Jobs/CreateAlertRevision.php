<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Internal\CampaignKeyword;
use App\Models\Internal\AlertRevision;
use App\Http\Controllers\Internal\GeoTargetController;
use App\Jobs\CrawlKeyword;
use Spatie\RateLimitedMiddleware\RateLimited;
use App\Repositories\UserAgent\UserAgentRepository;

class CreateAlertRevision implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $keyword;

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
    public function __construct(CampaignKeyword $keyword)
    {
        $this->keyword = $keyword;
    }

    /*
    * Determine the time at which the job should timeout.
    *
    */
//    public function retryUntil()
//    {
//        return now()->addHours(2);
//    }

//    public function middleware()
//    {
//        $shouldRateLimitJobs = config('api.rate_limiter');
//        $rateLimitedMiddleware = (new RateLimited())
//            ->enabled($shouldRateLimitJobs)
//            ->allow(8)
//            ->everyMinute()
//            ->releaseAfterBackoff($this->attempts(), 3);
//
//        return [$rateLimitedMiddleware];
//    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $geoController = new GeoTargetController;
        if(!empty($this->keyword->campaign->canonical_states)){
            $canonical_states = explode(',', $this->keyword->campaign->canonical_states);
            $geo_data = $geoController->findByCanonicalStates($canonical_states);
        } else {
            $canonical_country = explode(',', $this->keyword->campaign->country);
            $geo_data = $geoController->findByCanonicalCountry($canonical_country);
        }
        $user_agent = $this->getRandomUserAgent($this->keyword->device);
        $alertRevisionData = [
            'keyword_group_id' => $this->keyword->keyword_group_id,
            'keyword_id' => $this->keyword->id,
            'keyword' => $this->keyword->keyword,
            'canonical_states' => $geo_data[0]->canonical_states,
            'canonical_name' => $geo_data[0]->canonical_name,
            'google_uule' => isset($geo_data[0]->uule_code) ? $geo_data[0]->uule_code : 'w+CAIQICIaQXVzdGluLFRleGFzLFVuaXRlZCBTdGF0ZXM',
            'user_agent' => json_encode($user_agent),
            'status' => 'PENDING',
        ];
        $alertRevisionKeyword = AlertRevision::create($alertRevisionData);
        if($alertRevisionKeyword){
            CrawlKeyword::dispatch($this->keyword, $alertRevisionKeyword);
        }
    }

    public function getRandomUserAgent($device)
    {
        $userAgentRepo = new UserAgentRepository();
        if($device == 'desktop'){
            return $userAgentRepo->getDesktopUserAgent();
        } elseif ($device == 'mobile'){
            return $userAgentRepo->getMobileUserAgent();
        } else{
            return $userAgentRepo->getTabletUserAgent();
        }
        return $user_agent;
    }
}
