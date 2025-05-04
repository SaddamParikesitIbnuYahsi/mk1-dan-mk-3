<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Courier extends Model
{
    use HasFactory;

    protected $primaryKey = 'CourierID';

    protected $fillable = [
        'Name',
        'LicenseNumber',
        'VehicleType',
        'PhoneNumber',
    ];

     /**
      * Get all of the shipments for the Courier
      *
      * @return \Illuminate\Database\Eloquent\Relations\HasMany
      */
     public function shipments(): HasMany
     {
         return $this->hasMany(Shipment::class, 'CourierID', 'CourierID');
     }
}