<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'CustomerID';

    protected $fillable = [
        'Name',
        'Email',
        'PhoneNumber',
        'Address',
    ];

    /**
     * Get all of the packages for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packages(): HasMany
    {
        return $this->hasMany(Package::class, 'CustomerID', 'CustomerID');
    }
}