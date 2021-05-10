<?php

namespace App\Models\Internal\Keyword;

use App\Models\Internal\CampaignKeyword;
use App\Models\Internal\AlertRevision;
use App\Models\Internal\AdCompetition;
use App\Models\Internal\AdHijack;
use Illuminate\Database\Eloquent\Model;

class KeywordAd extends Model
{
    protected $fillable = [
        'id',
        'alert_id',
        'keyword_id',
        'keyword_group_id',
        'parent_id',
        'ad_type',
        'ad_position',
        'link',
        'ad_visible_link',
        'ad_link',
        'ad_title',
        'ad_text',
        'ad_status',
        'tries',
    ];

    public function keyword()
    {
        return $this->belongsTo(CampaignKeyword::class,'keyword_id');
    }

    public function alertRevision()
    {
        return $this->belongsTo(AlertRevision::class,'alert_id');
    }

    public function adTraces()
    {
        return $this->hasMany(AdTrace::class, 'ad_id');
    }

    public function adCompetitors()
    {
        return $this->hasMany(AdCompetition::class, 'keyword_ad_id');
    }

    public function adHijacks()
    {
        return $this->hasMany(AdHijack::class, 'ad_id');
    }
}
