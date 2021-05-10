<?php

namespace App\Models\Internal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'campaign_id',
        'filename',
        'file_hash',
        'hash',
        'status',
    ];
}
