<?php

namespace App\Models\Internal;

use Illuminate\Database\Eloquent\Model;
use App\Models\Internal\Keyword\KeywordAd;
use App\Models\Internal\Keyword\AdTrace;
use App\Models\Internal\AlertRevision;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignKeyword extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'campaign_id',
        'keyword_group_id',
        'keyword',
        'proxy_use',
        'device',
        'search_engine',
        'country_code',
        'lang',
        'google_domain',
        'deleted_at',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function keywordAds()
    {
        return $this->hasMany(KeywordAd::class, 'keyword_id');
    }

    public function keywordGroups()
    {
        return $this->belongsTo(KeywordGroup::class, 'keyword_group_id');
    }
    
    public function getHtml()
    {
        return $this->response_html;
    }   

    public function alertRevision()
    {
        return $this->hasMany(AlertRevision::class, 'keyword_id');
    }
}
