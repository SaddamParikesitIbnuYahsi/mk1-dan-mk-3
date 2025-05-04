<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $primaryKey = 'ShipmentID';

    protected $fillable = [
        'PackageID',
        'VendorID',
        'CourierID',
        'TrackingNumber',
        'DeliveryDate',
        'Status',
    ];

    /**
     * Get the package that owns the Shipment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'PackageID', 'PackageID');
    }

    /**
     * Get the vendor that handles the Shipment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'VendorID', 'VendorID');
    }

    /**
     * Get the courier that delivers the Shipment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'CourierID', 'CourierID');
    }
}