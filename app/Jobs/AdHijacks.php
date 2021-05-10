<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Internal\AlertRevision;
use App\Models\Internal\AdHijack;
use App\Models\Internal\Keyword\AdTrace;
use App\Models\Internal\Keyword\KeywordAd;
use App\Models\Internal\AdCompetition;

class AdHijacks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $alertRevision, $ad, $adCompetitions, $whitelisted_domains;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $deleteWhenMissingModels = true;

    public function __construct(AlertRevision $alertRevision, KeywordAd $ad, AdCompetition $adCompetitions = NULL, Array $whitelisted_domains)
    {
        $this->onQueue('tracer');
        $this->alertRevision = $alertRevision;
        $this->ad = $ad;
        $this->adCompetitions = $adCompetitions;
        $this->whitelisted_domains = $whitelisted_domains;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ad_traces = AdTrace::where('ad_id', $this->ad->id)->orderBy('id', 'ASC')->get();
        if(isset($this->adCompetitions)){
            foreach($ad_traces as $ad_trace){
                $this->checkCompetitonAdHijacks($ad_trace);
            }
        } else{
            foreach($ad_traces as $ad_trace){
                $this->checkAdHijacks($ad_trace);
            }
        }
    }

    public function checkAdHijacks($ad_trace)
    {
        /*$traced_domain = $this->getTracedDomain($ad_trace->traced_url);
        if($traced_domain == false){
            return;
        }

        $blacklisted_domains = $this->alertRevision->campaignKeyword->campaign->blacklisted_domain;
        if($blacklisted_domains != ''){
            $blacklisted_domains = explode(',', $blacklisted_domains);
            if (in_array($traced_domain, $blacklisted_domains)){
                $this->saveAdHijacks($ad_trace, $traced_domain);
            }
        }
        $whitelisted_domains = $this->getWhitelistedDomains();

        if(!in_array($traced_domain, $whitelisted_domains)){
            $this->saveAdHijacks($ad_trace, $traced_domain);
        } */
        $traced_domain = $ad_trace->traced_url;
        if($traced_domain == false){
            return;
        }
        //Blacklisted wildcard Match
        $blacklisted_domains = $this->alertRevision->campaignKeyword->campaign->blacklisted_domain;
        if($blacklisted_domains != ''){
            $blacklisted_domains = explode(',', $blacklisted_domains);
            $blacklisted_flag = array_reduce($blacklisted_domains, function ( $isTrue, $blacklisted_domains) use ($traced_domain) {
                return $isTrue || $this->isMatching($traced_domain , $blacklisted_domains);
            });
            if ($blacklisted_flag === true){
                $this->saveAdHijacks($ad_trace, $traced_domain);
            }
        }
        //Whitelisted wildcard match
        $whitelisted_domains = $this->getWhitelistedDomains();
        $flag = array_reduce($whitelisted_domains, function ( $isTrue, $whitelisted_domains) use ($traced_domain) {
            return $isTrue || $this->isMatching($traced_domain , $whitelisted_domains);
        });
        if($flag === false){
            $this->saveAdHijacks($ad_trace, $traced_domain);
        }
    }

    public function checkCompetitonAdHijacks($ad_trace)
    {
        /*$traced_domain = $this->getTracedDomain($ad_trace->traced_url);
        if($traced_domain == false){
            return;
        }*/
        $whitelisted_domains = $this->ignoreSystemWhitelistedDomains();
        $traced_domain = $ad_trace->traced_url;
        if($traced_domain == ''){
            return;
        }
        $flag = array_reduce($whitelisted_domains, function ( $isTrue, $whitelisted_domains) use ($traced_domain) {
            return $isTrue || $this->isMatching($traced_domain , $whitelisted_domains);
        });
        //hijack (contains any whitelisted domains)
        if($flag === true){
        //if(in_array($traced_domain, $whitelisted_domains)){
            $this->saveAdHijacks($ad_trace, $traced_domain);
            $removeCompetition = $this->adCompetitions->delete();
        }
    }

    public function getWhitelistedDomains()
    {
        $system_whitelisted_domains = config('api.tracer.whitelisted_domains');
        if(!empty($system_whitelisted_domains)){
            $whitelisted_domains = array_merge( $system_whitelisted_domains, $this->whitelisted_domains);
        } else {
            $whitelisted_domains = $this->whitelisted_domains;
        }
        return $whitelisted_domains;
    }

    public function ignoreSystemWhitelistedDomains()
    {
        $system_whitelisted_domains = config('api.tracer.whitelisted_domains');
        if(!empty($system_whitelisted_domains)){
            $whitelisted_domains = array_diff( $this->whitelisted_domains, $system_whitelisted_domains );
        } else {
            $whitelisted_domains = $this->whitelisted_domains;
        }
        return $whitelisted_domains;
    }
    
    public function saveAdHijacks($ad_trace, $traced_domain)
    {
        $adhijackData = [
            'ad_trace_id' => $ad_trace->id,
            'ad_id' => $ad_trace->ad_id,
            'campaign_id' => $this->alertRevision->campaignKeyword->campaign->id,
            'traced_domain' => $traced_domain,
        ];
        $hijack = AdHijack::create($adhijackData);
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
}
