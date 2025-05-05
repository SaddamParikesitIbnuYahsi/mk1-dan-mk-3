<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'license_number', 'vehicle_type', 'phone_number'];

    public function orders()
    {
        return $this->hasOne(Order::class);
    }

}