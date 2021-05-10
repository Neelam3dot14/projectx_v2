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
        if (isset($campaigns[0]->whitelisted_domain)){
            $whitelisted_domains = str_replace(",", PHP_EOL, $campaigns[0]->whitelisted_domain);
        } else {
            $whitelisted_domains = '';
        }

        if (isset($campaigns[0]->blacklisted_domain)){
            $blacklisted_domains = str_replace(",", PHP_EOL, $campaigns[0]->blacklisted_domain);
        } else {
            $blacklisted_domains = '';
        }
        $campData = [
            'id' => $campaigns[0]->id,
            'name' => $campaigns[0]->name,
            'keywords' => $campaigns[0]->keywords,
            'device' => explode(",", $campaigns[0]->device),
            'search_engine' => explode(",", $campaigns[0]->searchEngine),
            'crawler' => $campaigns[0]->crawler,
            'execution_interval' => $campaigns[0]->execution_interval,
            'country' => explode(",", $campaigns[0]->country),
            'states' => isset($campaigns[0]->canonical_states) ? explode(",", $campaigns[0]->canonical_states) : '',
            'country_location' => $campaigns[0]->location,
            'state_location' => isset($campaigns[0]->canonical_states) ? $campaigns[0]->location : '',
            'location' => $campaigns[0]->location,
            'whitelisted_domain' => $whitelisted_domains,
            'blacklisted_domain' => $blacklisted_domains,
        ];
        return Inertia::render('Internal/Campaigns/CampaignEdit', [ 'campaign' => $campData ]);
    }

    public function update(Request $request, Campaign $campaign)
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
            $geodata = json_encode($data['country']);
        }
        $data['search_engine'] = strtolower(implode(",", $data['search_engine']));
        $data['device'] = strtolower(implode(",", $data['device']));
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
        return redirect()->route('internal.campaign.list')
            ->with(['message'=> 'Campaign Edited Successfully.', 'campaigns' => $campaign]);
    }

    public function destroy($campaign_id)
    {
        $result = Campaign::where('id', $campaign_id)
                    ->where('created_by',\Auth::id())
                    ->delete();
        if($result){
            $response = [
                'msg' => 'campaign Deleted'
            ];
        }
        else{
            $response = [
                'error' => 'campaign not Deleted'
            ];
        }
        return response($response, 201);
    }
    
    public function pause($campaign_id)
    {
        $this->campaignRepo = new CampaignRepository();
        $result = $this->campaignRepo->pauseCampaign($campaign_id);

        if($result){
            $response = [
                'msg' => 'Campaign Successfully Paused'
            ];
        }
        else{
            $response = [
                'error' => 'Campaign Pause Face Issue'
            ];
        }

        return response($response, 201);
    }

    public function reActivate($campaign_id)
    {
        $this->campaignRepo = new CampaignRepository();
        $result = $this->campaignRepo->activateCampaign($campaign_id);

        if($result){
            $response = [
                'msg' => 'Campaign Successfully Re-Activated'
            ];
        }
        else{
            $response = [
                'error' => 'Campaign Re-Activation Facing Issue'
            ];
        }

        return response($response, 201);
    }

    public function execute($campaign_id)
    {
        $this->campaignRepo = new CampaignRepository();
        $this->campaignRepo->executeKeywordsProcessing($campaign_id);
        $result = Campaign::find($campaign_id)
                    ->update(['status' => 'ACTIVE']);
        $response = [
            'msg' => 'Added in Execution Queue'
        ];
        return response($response, 200);
    }

    public function export($campaign_id)
    {
        $fileName = 'Campaign Report '.$campaign_id.'csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $this->campaignRepo = new CampaignRepository();
        $callback = $this->campaignRepo->exportCampaign($campaign_id);
        return response()->stream($callback, 200, $headers);
    }

    public function exportAll(Request $request)
    {
        ini_set('memory_limit', '-1');
        $fileName = 'Campaigns Report'.'csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $this->campaignRepo = new CampaignRepository();
        $user_id = \Auth::id();
        $callback = $this->campaignRepo->exportAllCampaign($user_id);
        return response()->stream($callback, 200, $headers);
    }



}
