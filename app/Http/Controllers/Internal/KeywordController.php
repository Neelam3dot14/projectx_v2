<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Internal\AlertRevision;
use App\Models\Internal\KeywordGroup;
use App\Http\Resources\Keyword\KeywordAdsResource;
use App\Models\Internal\Keyword\KeywordAd;
use App\Models\Internal\CampaignKeyword;
use App\Http\Resources\Keyword\KeywordAdTraceResource;

class KeywordController extends Controller
{
    public function getKeywordHtml($alert_id)
    {
        $keywordHtml = AlertRevision::select('id', 'crawled_html')->where('id', $alert_id)
                ->get();
        $crawled_html = $keywordHtml[0]->crawled_html;
        return Inertia::render('Internal/Keyword/KeywordHtml', ['crawled_html' => $crawled_html]);
    }

    public function getKeywordAds(Request $request, $keywordGroupId)
    {
        $keywordAds = KeywordGroup::find($keywordGroupId)
            ->keywordGroupAd()
            ->groupBy('ad_text')
            ->selectRaw("keyword_ads.id, alert_id, keyword_ads.keyword_group_id, GROUP_CONCAT(DISTINCT keyword_ads.id SEPARATOR ',') AS keyword_ads_id, GROUP_CONCAT(DISTINCT keyword_id SEPARATOR ',') AS keyword_id, GROUP_CONCAT(DISTINCT device SEPARATOR ',') AS device, GROUP_CONCAT(DISTINCT search_engine SEPARATOR ',') AS search_engine, count(*) as ad_count, ad_type, ad_position, link, ad_visible_link, ad_link, ad_text, ad_title, ad_status")
            ->withCount('adTraces', 'adCompetitors', 'adHijacks')
            ->get();
        return KeywordAdsResource::collection($keywordAds);
    }

    public function getAllKeywordAds(Request $request, $keyword_group_id, $id)
    {
        $KeywordGroup = CampaignKeyword::selectRaw("GROUP_CONCAT(DISTINCT id SEPARATOR ',') AS key_id, keyword_group_id, count(*) as total")
                    ->where('keyword_group_id', $keyword_group_id)
                    ->groupBy('keyword_group_id')->get();
        $keyword_id = explode(",", $KeywordGroup[0]->key_id);     //array of keyword_id
        $keywordAd = KeywordAd::where('id', $id)->get();
        $ad_text = $keywordAd[0]->ad_text;
        
        $keywordAdInstances = KeywordAd::whereIn('keyword_id', $keyword_id)->where('ad_text', $ad_text)
            ->with('keyword','alertRevision')
            ->withCount('adTraces')
            ->get();

        date_default_timezone_set('Asia/Kolkata');
        return Inertia::render('Internal/Keyword/KeywordAdInstance', [
            'keywordAds' => $keywordAdInstances->map(function ($ad) {
                return [
                'id' => $ad->id,
                'alert_id' => $ad->alert_id,
                'keyword_id' => $ad->keyword_id,
                'device' => $ad->keyword->device,
                'search_engine' => $ad->keyword->search_engine,
                'crawled_html' => $ad->alertRevision->crawled_html,
                'ad_title' => $ad->ad_title,
                'ad_type' => $ad->ad_type,
                'ad_visible_link' => $ad->ad_visible_link,
                'ad_position' => $ad->ad_position,
                'ad_link' => $ad->ad_link,
                'ad_text' => $ad->ad_text,
                'ad_status' => $ad->ad_status,
                'created_at' => date('d-m-Y h:i:s', strtotime($ad->created_at)),
                'ad_traces_count' => $ad->ad_traces_count,
                ];
            }),
        ]);
    }

    public function getAdTraces(Request $request, $id)
    {
        $ad_details = KeywordAd::find($id)->adTraces()->get();
        return KeywordAdTraceResource::collection($ad_details);
    }
}
