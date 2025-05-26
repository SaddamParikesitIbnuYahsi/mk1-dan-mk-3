<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'customer_id',
        'weight',
        'package_type',
        'shipping_cost',
        'receipt_date',
    ];

    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
}
