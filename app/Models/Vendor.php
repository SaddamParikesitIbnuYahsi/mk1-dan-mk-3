<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $primaryKey = 'VendorID';

    protected $fillable = [
        'BusinessName',
        'LicenseNumber',
        'Address',
    ];

    /**
     * Get all of the shipments for the Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'VendorID', 'VendorID');
    }
}