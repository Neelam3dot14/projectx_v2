<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Internal\AlertRevision;
use App\Models\Internal\AdCompetition;
use App\Models\Internal\Keyword\KeywordAd;
use App\Jobs\AdHijacks;

class AdsCompetition implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $alertRevision, $ad;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $deleteWhenMissingModels = true;


    public function __construct(AlertRevision $alertRevision, KeywordAd $ad)
    {
        $this->onQueue('tracer');
        $this->alertRevision = $alertRevision;
        $this->ad = $ad;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $campaign_whitelisted_domains = $this->alertRevision->campaignKeyword->campaign->whitelisted_domain;
        if($campaign_whitelisted_domains == ''){
            return;
        } 
        $camp_whitelisted_domains = explode(',', $campaign_whitelisted_domains);
        $whitelisted_domains = $this->ignoreSystemWhitelistedDomains($camp_whitelisted_domains);
        $url = $this->ad->ad_visible_link;
        if($url == ''){
            return;
        }
        //$ad_visible_domain = $this->getVisibleDomain($this->ad->ad_visible_link);
        $flag = array_reduce($whitelisted_domains, function ( $isTrue, $whitelisted_domains) use ($url) {
            return $isTrue || $this->isMatching($url, $whitelisted_domains);
        });
        if($flag === false){
        //if(!in_array($ad_visible_domain, $whitelisted_domains)){
            $competitorData = [
                'keyword_ad_id' => $this->ad->id,
                'campaign_id' => $this->alertRevision->campaignKeyword->campaign->id,
                'ad_domain' => $this->ad->ad_visible_link,
            ];
            $ad_competitions = AdCompetition::create($competitorData);
            if($ad_competitions){
                AdHijacks::dispatch($this->alertRevision, $this->ad, $ad_competitions, $whitelisted_domains);
            }
        } else{
            AdHijacks::dispatch($this->alertRevision, $this->ad, null, $whitelisted_domains);
        }
    }

    public function isMatching($needle,  $haystack)
    {
        $wildcard = strpos($haystack, '*');  
        if( $wildcard === false ){
            $haystack_items = $this->parseUrl($haystack);
            $haystackdomain = $haystack_items['host'];
    
            $needle_items = $this->parseUrl($needle);
            $needleDomain = $needle_items['host'];
            
            if( !isset($haystack_items['path']) || empty($haystack_items['path']) ){
                return $needleDomain == $haystackdomain;
            }
            return $needle == $haystack;
        }
    
        $haystackParts = $this->getDomainParts($haystack);
        $needleParts = $this->getDomainParts($needle);
        
    
        $haystackPartsCount = count($haystackParts);
        $needlePartsCount = count($needleParts);
    
        if( $haystackPartsCount > $needlePartsCount ){
            return false;
        }
    
        $wildcardPosition = array_search('*', $haystackParts);
        $haystack_domain = array_slice($haystackParts, $wildcardPosition + 1, $haystackPartsCount );
        $needle_domain = array_slice($needleParts, -1 * count($haystack_domain));
        return count(array_diff($needle_domain, $haystack_domain)) === 0;
    }
    

    public function cleanUrl($input)
    {
        // If scheme not included, prepend it
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }
    }

    public function getDomainParts($url)
    {
        $parsed_url = $this->parseUrl($url);
        return explode('.', $parsed_url['host']);
    }

    public function parseUrl($url)
    {
        $data = parse_url($this->addhttp($url));
        $data['host'] = preg_replace('/^www\./', '', $data['host']);
        return $data;
    }

    public function addhttp($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        return $url;
    }

    public function ignoreSystemWhitelistedDomains($camp_whitelisted_domains)
    {
        $system_whitelisted_domains = config('api.tracer.whitelisted_domains');
        if(!empty($system_whitelisted_domains)){
            $whitelisted_domains = array_diff( $camp_whitelisted_domains, $system_whitelisted_domains );
        } else {
            $whitelisted_domains = $camp_whitelisted_domains;
        }
        return $whitelisted_domains;
    }
}
