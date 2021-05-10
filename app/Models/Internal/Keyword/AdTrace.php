<?php

namespace App\Models\Internal\Keyword;

use Illuminate\Database\Eloquent\Model;

class AdTrace extends Model
{
    protected $fillable = [
        'id',
        'ad_id',
        'traced_url',
        'context',
        'redirect_type',
    ];

    public function keywordAd(){
        return $this->belongsTo(KeywordAd::class,'ad_id');
    }
}
