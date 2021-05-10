<?php

namespace App\Models\Internal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateNetwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];
}
