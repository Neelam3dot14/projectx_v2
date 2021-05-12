<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Internal\Campaign;
use App\Models\Internal\Keyword\KeywordAd;
use Inertia\Inertia;

class KeywordAdsController extends Controller
{
    public function getAdTraces(Request $request, $id)
    {
        $ad_details = KeywordAd::find($id)->adTraces()->get();
        return AdTraceResource::collection($ad_details);
    }

    public function getAdHijacks($campaign_id)
    {
        $keyword_ad = Campaign::find($campaign_id)->adHijacks()->groupBy('ad_id')->with('keywordAds')->get();
        return Inertia::render('Internal/Campaigns/AdHijack', [
            'adHijacks' => $keyword_ad->map(function ($ad) {
                return [
                    'id' => $ad->keywordAds->id,
                    'keyword_id' => $ad->keywordAds->keyword_id,
                    'keyword_group_id' => $ad->keywordAds->keyword_group_id,
                    'ad_title' => $ad->keywordAds->ad_title,
                    'ad_type' => $ad->keywordAds->ad_type,
                    'ad_visible_link' => $ad->keywordAds->ad_visible_link,
                    'ad_position' => $ad->keywordAds->ad_position,
                    'ad_link' => $ad->keywordAds->ad_link,
                    'ad_text' => $ad->keywordAds->ad_text,
                    'ad_status' => $ad->keywordAds->ad_status,
                ];
            })
        ]);
    }

    public function getAdHijackTraces($ad_id)
    {
        $hijack_trace_details = AdTrace::from( 'ad_traces as A' )->where('A.ad_id', $ad_id)
            ->select('A.id', 'A.ad_id', 'A.traced_url', 'A.context', 'A.redirect_type', 'B.id as hijack_id', 'B.campaign_id', 'B.traced_domain', 'B.created_at')
            ->leftJoin("ad_hijacks as B", "A.id", "=", "B.ad_trace_id")    
            ->get();
        return AdHijackTraceResource::collection($hijack_trace_details);
    }

    public function getAdCompetitors($campaign_id)
    {
        $keyword_ad = Campaign::find($campaign_id)->adCompetitors()->with('keywordAds')->get();
        return Inertia::render('Internal/Campaigns/AdCompetitions', [
            'adCompetitions' => $keyword_ad->map(function ($ad) {
                return [
                    'id' => $ad->keywordAds->id,
                    'keyword_id' => $ad->keywordAds->keyword_id,
                    'keyword_group_id' => $ad->keywordAds->keyword_group_id,
                    'ad_title' => $ad->keywordAds->ad_title,
                    'ad_type' => $ad->keywordAds->ad_type,
                    'ad_visible_link' => $ad->keywordAds->ad_visible_link,
                    'ad_position' => $ad->keywordAds->ad_position,
                    'ad_link' => $ad->keywordAds->ad_link,
                    'ad_text' => $ad->keywordAds->ad_text,
                    'ad_status' => $ad->keywordAds->ad_status,
                ];
            })
        ]);
    }
}
