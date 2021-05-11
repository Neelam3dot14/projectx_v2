<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Internal\AlertRevision;

class KeywordController extends Controller
{
    public function getKeywordHtml($alert_id)
    {
        $keywordHtml = AlertRevision::select('id', 'crawled_html')->where('id', $alert_id)
                ->get();
        $crawled_html = $keywordHtml[0]->crawled_html;
        return Inertia::render('Internal/Keyword/KeywordHtml', ['crawled_html' => $crawled_html]);
    }
}
