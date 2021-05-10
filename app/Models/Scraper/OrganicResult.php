<?php

namespace App\Models\Scraper;

use Illuminate\Database\Eloquent\Model;

class OrganicResult extends Model
{
    protected $fillable = [
        'link',
        'title',
        'snippet',
        'visible_link',
        'date',
        'rank',
    ];
}
