<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cs extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'contactperson',
        'contactnumber'
    ];
            // Relasi dengan Customers
            public function customers()
            {
                return $this->belongsTo(Customer::class);
            }
}