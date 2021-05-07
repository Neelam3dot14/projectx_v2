<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Internal\Campaign;
use Inertia\Inertia;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::all();
        return Inertia::render('Internal/Campaigns/CampaignIndex', ['campaigns' => $campaigns]);
    }

    public function create()
    {
        return Inertia::render('Internal/Campaigns/CampaignCreate');
    }
    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'keywords' => 'required',
            'device' => 'required',
            'country' => 'required',
            'execution_interval' => 'required',
            'search_engine' => 'required',
            'geotarget_search' => 'nullable|',
            'crawler' => 'nullable',
            'whitelisted_domain' => 'nullable',
            'blacklisted_domain' => 'nullable',
        ]);

        if (isset($data['whitelisted_domain'])){
            $whitelisted_domains = explode(PHP_EOL, $data['whitelisted_domain']);
            $whitelisted_domains = implode(",", array_filter($whitelisted_domains));
        } else {
            $whitelisted_domains = '';
        }

        if (isset($data['blacklisted_domain'])){
            $blacklisted_domains = explode(PHP_EOL, $data['blacklisted_domain']);
            $blacklisted_domains = implode(",", array_filter($blacklisted_domains));
        } else {
            $blacklisted_domains = '';
        }

        $crawler = empty($data['crawler']) ? 'random' : implode(",", $data['crawler']);
        //If state non empty
        /*if(!empty($data['geotarget_search'])){
            //get gl_code,domain and canonical_name from geotarget_search
            foreach($data['geotarget_search'] as $v){
                $canonical_states[] = $v['canonical_states'];
                $gl_code[] = $v['country_code'];
                $google_domain[] = $v['google_domain'];
            }
            $canonical_states = implode(",", $canonical_states);
            $gl_code = implode(",", $gl_code);
            $google_domain = implode(",", $google_domain);
            $geodata = json_encode($data['geotarget_search']);
        }
        //if country set but state empty
        else{
            foreach($data['country'] as $v){
                //$country_code[] = $country['country_code'];
                $gl_code[] = $v['country_code'];
                $google_domain[] = $v['google_domain'];
            }
            $gl_code = implode(",", $gl_code);
            $google_domain = implode(",", $google_domain);
            $geodata = json_encode($data['country']);
        }*/
        $gl_code = "IN";
        $google_domain = "google.com";
        $geodata = "test";
        $searchEngine = strtolower($data['search_engine']);//strtolower(implode(",", $data['search_engine']));
        $device = $data['device'];//implode(",",$data['device']);
        $country = $data['country'];
        /*foreach($data['country'] as $country){
            $country_code[] = $country['country_code'];
        }
        $country = implode(",", $country_code);*/
        $current_user_id = \Auth::id();
        $campData = [
            'name' => $data['name'],
            'keywords' => trim($data['keywords']),
            'device' => $device,
            'search_engine' => trim($searchEngine),
            'crawler' => $crawler,
            'execution_type' => 'Crawl',
            'execution_interval' => $data['execution_interval'],
            'country' => $country,
            'canonical_states' => isset($canonical_states) ? $canonical_states : '',
            'gl_code' => $gl_code,
            'google_domain' => $google_domain,
            'location' => $geodata,
            'created_by' => $current_user_id,
            'whitelisted_domain' => $whitelisted_domains,
            'blacklisted_domain' => $blacklisted_domains,
        ];
        $campaign = Campaign::create($campData);
        return redirect()->route('internal.campaign.list')
            ->with('message', 'Campaign Created Successfully.');
        //event(CampaignKeywordGroupEvents::CAMPAIGN_KEYWORD_GROUP_CREATED, new CampaignKeywordGroupEvents($campaign));
    }
}
