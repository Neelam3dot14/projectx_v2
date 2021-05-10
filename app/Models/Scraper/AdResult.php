<?php

namespace App\Models\Scraper;

use Illuminate\Database\Eloquent\Model;

class AdResult extends Model
{
    protected $fillable = [
        'position',
        'visible_link',
        'tracking_link',
        'link',
        'title',
        'snippet',
        'sub_links',
    ];
}
