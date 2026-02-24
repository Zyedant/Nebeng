<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refunds';

    protected $fillable = [
        'order_id', 'reason', 'status'
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\Pesanan::class, 'order_id');
    }
}
