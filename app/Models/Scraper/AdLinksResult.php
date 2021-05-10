<?php

namespace App\Models\Scraper;

use Illuminate\Database\Eloquent\Model;

class AdLinksResult extends Model
{
    protected $fillable = [
        'tracking_link',
        'link',
        'title',
        'snippet',
    ];
}
