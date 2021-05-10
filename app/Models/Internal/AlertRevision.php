<?php

namespace App\Models\Internal;

use App\Models\Keyword\AdTrace;
use App\Models\Keyword\KeywordAd;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'keyword_group_id',
        'keyword_id',
        'keyword',
        'canonical_name',
        'canonical_states',
        'google_uule',
        'user_agent',
        'crawled_html',
        'crawling_error',
        'scraped_json',
        'scraping_error',
        'tracing_error',
        'crawler_metadata',
        'scraper_metadata',
        'tracer_metadata',
        'status',
        'global_tries',
        'crawler_tries',
        'scraper_tries',
        'tracer_tries',
    ];
    //protected $appends = ['crawling_error', 'crawler_metadata'];

    public function getHtml()
    {
        return $this->crawled_html;
    }

    public function campaignKeyword()
    {
        return $this->belongsTo(CampaignKeyword::class, 'keyword_id');
    }

    public function keywordAds()
    {
        return $this->hasMany(KeywordAd::class, 'alert_id');
    }


}
