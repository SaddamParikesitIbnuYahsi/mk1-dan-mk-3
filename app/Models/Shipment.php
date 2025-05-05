<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'OrderID',
        'VendorID',
        'CourierID',
        'TrackingNumber',
        'DeliveryDate',
        'Status'
    ];

    // Relasi ke model Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID');
    }

    // Relasi ke model Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'VendorID');
    }

    // Relasi ke model Courier
    public function courier()
    {
        return $this->belongsTo(Courier::class, 'CourierID');
    }
}
