<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'actor_name',
        'type',
        'title',
        'description',
        'page',
        'ref_type',
        'ref_id',
        'is_read',
    ];
}
