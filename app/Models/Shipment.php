<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'vendor_id',
        'courier_id',
        'tracking_number',
        'delivery_date',
        'status',
    ];

    // Relasi ke Package
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Relasi ke Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relasi ke Courier
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }
}
