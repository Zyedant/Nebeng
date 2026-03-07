<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pesanan extends Model
{
    // ✅ sesuai DB: pesanans
    protected $table = 'pesanan';

    protected $fillable = [
        'order_no','customer_id','mitra_id','layanan','harga','status','catatan','tanggal',
         'pickup_address','dropoff_address',
        'pickup_lat','pickup_lng','dropoff_lat','dropoff_lng',
        'distance_km','duration_min',
        'payment_method','transaction_no',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'harga'   => 'integer', // opsional tapi bagus
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }
}
