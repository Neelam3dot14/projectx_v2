<?php

namespace App\Models\Internal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Internal\Keyword\KeywordAd;

class AdHijack extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'ad_trace_id',
        'ad_id',
        'campaign_id',
        'traced_domain',
    ];

    public function keywordAds()
    {
        return $this->belongsTo(KeywordAd::class, 'ad_id');
    }
}
