<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Internal\Campaign;
use App\Events\CampaignKeywordGroupEvents;
use App\Repositories\CampaignRepository;
use App\Http\Resources\ViewCampaignResource;

class CampaignController extends Controller
{
    public $campaignRepo;

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
            'search_engine' => 'required',
            'country' => 'required',
            'states' => 'nullable|',
            'execution_interval' => 'required',
            'crawler' => 'nullable',
            'blacklisted_domain' => 'nullable',
            'whitelisted_domain' => 'nullable',
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
        if(!empty($data['states'])){
            //get gl_code,domain and canonical_name from states
            foreach($data['states'] as $v){
                $canonical_states[] = $v['canonical_states'];
                $gl_code[] = $v['country_code'];
                $google_domain[] = $v['google_domain'];
            }
            $canonical_states = implode(",", $canonical_states);
            $gl_code = implode(",", $gl_code);
            $google_domain = implode(",", $google_domain);
            $geodata = json_encode($data['states']);
        }
        //if country set but state empty
        else{
            foreach($data['country'] as $v){
                $gl_code[] = $v['country_code'];
                $google_domain[] = $v['google_domain'];
            }
            $gl_code = implode(",", $gl_code);
            $google_domain = implode(",", $google_domain);
            $geodata = json_encode($data['country']);
        }
        $searchEngine = strtolower(implode(",", $data['search_engine']));
        $device = implode(",", $data['device']);
        $country = $data['country'];
        foreach($data['country'] as $country){
            $country_code[] = $country['country_code'];
        }
        $country = implode(",", $country_code);
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
        event(CampaignKeywordGroupEvents::CAMPAIGN_KEYWORD_GROUP_CREATED, new CampaignKeywordGroupEvents($campaign));
        return redirect()->route('internal.campaign.list')
            ->with('message', 'Campaign Created Successfully.');
    }

    public function show(Campaign $campaign)
    {
        $this->campaignRepo = new CampaignRepository();
        $campaigns = $this->campaignRepo->view($campaign->id);
        return Inertia::render('Internal/Campaigns/CampaignEdit', ['campaign' => $campaigns[0] ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'keywords' => 'required',
            'device' => 'required',
            'search_engine' => 'required',
            'country' => 'required',
            'states' => 'nullable',
            'execution_interval' => 'required',
            'whitelisted_domain'  => 'nullable',
            'blacklisted_domain' => 'nullable',
        ]);
        
        if(!empty($data['states'])){
            foreach($data['states'] as $v){
                $canonical_states[] = $v['canonical_states'];
                $gl_code[] = $v['country_code'];
                $google_domain[] = $v['google_domain'];
            }
            $canonical_states = implode(",", $canonical_states);
            $gl_code = implode(",", $gl_code);
            $google_domain = implode(",", $google_domain);
            $geodata = json_encode($data['states']);
        } //if country set but state empty
        else{
            foreach($data['country'] as $v){
                $gl_code[] = $v['country_code'];
                $google_domain[] = $v['google_domain'];
            }
            $gl_code = implode(",", $gl_code);
            $google_domain = implode(",", $google_domain);
            $geodata = json_encode($data['country_location']);
        }
        $data['search_engine'] = strtolower($data['search_engine']);
        $data['country'] = $gl_code;
        $data['canonical_states'] = isset($canonical_states) ? $canonical_states : '';
        $data['gl_code'] = $gl_code;
        $data['google_domain'] = $google_domain;
        $data['location'] = $geodata;
        if (isset($data['whitelisted_domain'])){
            $whitelisted_domains = explode(PHP_EOL, $data['whitelisted_domain']);
            $whitelisted_domains = implode(",", array_filter($whitelisted_domains));
            $data['whitelisted_domain'] = $whitelisted_domains;
        } else {
            $data['whitelisted_domain'] = '';
        }

        if (isset($data['blacklisted_domain'])){
            $blacklisted_domains = explode(PHP_EOL, $data['blacklisted_domain']);
            $blacklisted_domains = implode(",", array_filter($blacklisted_domains));
            $data['blacklisted_domain'] = $blacklisted_domains;
        } else {
            $data['blacklisted_domain'] = '';
        }
        
        $old_keyword  = explode(',', $campaign->keywords);
        $new_keyword =  explode(',', $data['keywords']);
        $added_keywords = array_diff($new_keyword, $old_keyword);
        $removed_keywords = array_diff($old_keyword, $new_keyword);
        $matched_keywords =  array_intersect($new_keyword, $old_keyword);
        $this->campaignRepo = new CampaignRepository();
        $this->campaignRepo->addNewKeyword($campaign, $added_keywords, $data);
        $this->campaignRepo->removeKeyword($campaign, $removed_keywords);
        $this->campaignRepo->updateMatchKeywords($campaign, $matched_keywords, $data);
        
        $campaign->update($data);
        return Inertia::render('Internal/Campaigns/CampaignIndex');
    }
}
