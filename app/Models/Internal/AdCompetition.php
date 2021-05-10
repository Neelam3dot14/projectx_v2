<?php

namespace App\Models\Internal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Internal\Keyword\KeywordAd;

class AdCompetition extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'keyword_ad_id',
        'campaign_id',
        'ad_domain',
    ];

    public function keywordAds()
    {
        return $this->belongsTo(KeywordAd::class, 'keyword_ad_id');
    }
}
