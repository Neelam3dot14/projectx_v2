<?php

namespace App\Models\Scraper;

use Illuminate\Database\Eloquent\Model;

class ScraperResult extends Model
{
    protected $fillable = [
        'keyword',
        'organic_results',
        'meta_results',
        'ad_results',
    ];
}
