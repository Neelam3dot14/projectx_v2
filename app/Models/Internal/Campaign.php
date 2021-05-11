<?php

namespace App\Models\Internal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'keywords',
        'device',
        'search_engine',
        'crawler',
        'execution_type',
        'execution_interval',
        'country',
        'canonical_states',
        'gl_code',
        'google_domain',
        'location',
        'blacklisted_domain',
        'whitelisted_domain',
        'status',
        'created_by',
    ];

    public function keywords()
    {
        return $this->hasMany(CampaignKeyword::class);
    }

    public function keywordGroups()
    {
        return $this->hasMany(KeywordGroup::class);
    }
    
    public function keywordAds()
    {
        return $this->hasManyThrough(KeywordAd::class, CampaignKeyword::class, 'campaign_id', 'keyword_id' );
    }

    public function alertRevisions()
    {
        return $this->hasManyThrough(AlertRevision::class, CampaignKeyword::class, 'campaign_id', 'keyword_id');
    }

    public function adCompetitors()
    {
        return $this->hasMany(AdCompetition::class);
    }

    public function adHijacks()
    {
        return $this->hasMany(AdHijack::class);
    }
}
