<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'Name',
        'LicenseNumber',
        'VehicleType',
        'PhoneNumber'
    ];

    public function orders()
    {
        return $this->hasOne(Order::class);
    }

}