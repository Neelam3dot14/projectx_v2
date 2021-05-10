<?php

namespace App\Models\Internal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Internal\Keyword\KeywordAd;
use App\Models\Internal\CampaignKeyword;

class KeywordGroup extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id',
        'campaign_id',
        'keyword',
        'device',
        'search_engine',
        'country_code',
        'states',
        'location'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function campaignKeywords()
    {
        return $this->hasMany(CampaignKeyword::class, 'keyword_group_id');
    }

   /* public function keywordGroupAd()
    {
        return $this->hasManyThrough(
            KeywordAd::class,
            CampaignKeyword::class,
            'keyword_group_id', // Foreign key on the campaignKeyword table...
            'keyword_id', // Foreign key on the KeywordAd table...
            //'id', // Local key on the CampaignKeyword  table...
            //'id' // Local key on the KeywordAd table...
        );
    }*/
}
