<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'partner_id',
        'departure_post_id',
        'destination_post_id',
        'date',
        'time'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function departurePost()
    {
        return $this->belongsTo(PartnerPost::class, 'departure_post_id');
    }

    public function destinationPost()
    {
        return $this->belongsTo(PartnerPost::class, 'destination_post_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

    public function getInvoiceAttribute()
    {
        return 'TRX-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    public function getOrderNumberAttribute()
    {
        return 'ORD-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    public function getTransactionStatusAttribute()
    {
        if ($this->refund && $this->refund->status === 'Diterima') {
            return 'batal';
        }
        
        if ($this->payment) {
            if ($this->payment->status === 'Ditolak') {
                return 'batal';
            }
            if ($this->payment->status === 'Diterima') {
                return 'selesai';
            }
        }
        
        return 'proses';
    }

    public function isPaid()
    {
        return $this->payment && $this->payment->status === 'Diterima';
    }

    public function hasPayment()
    {
        return $this->payment !== null;
    }

    public function getPaymentAmountAttribute()
    {
        return $this->payment ? $this->payment->payment_amount : 0;
    }

    public function getPaymentAmountFormattedAttribute()
    {
        return $this->payment ? $this->payment->payment_amount_formatted : 'Rp 0';
    }

    public function getPaymentMethodAttribute()
    {
        return $this->payment ? $this->payment->payment_method : 'Belum Bayar';
    }

    public function getPaymentMethodTextAttribute()
    {
        return $this->payment ? $this->payment->payment_method_text : 'Belum Bayar';
    }

    public function getPaymentStatusAttribute()
    {
        return $this->payment ? $this->payment->status : 'Belum Bayar';
    }

    public function getPaymentStatusTextAttribute()
    {
        return $this->payment ? $this->payment->status_text : 'Belum Bayar';
    }
}