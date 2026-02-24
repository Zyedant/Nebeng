<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerVehicle extends Model
{
    protected $table = 'partner_vehicles';

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
