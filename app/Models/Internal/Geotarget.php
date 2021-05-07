<?php

namespace App\Models\Internal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geotarget extends Model
{
    use HasFactory;
    protected $fillable = [
        'yahoo_domain',
    ];
    public $timestamps = false;
}
