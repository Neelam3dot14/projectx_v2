<?php

namespace App\Models\Scraper;

use Illuminate\Database\Eloquent\Model;

class MetaResult extends Model
{
    protected $fillable = [
        'num_results',
        'effective_query',
        'time',
    ];
}
