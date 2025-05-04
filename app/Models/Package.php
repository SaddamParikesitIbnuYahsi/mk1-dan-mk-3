<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $primaryKey = 'PackageID';

    protected $fillable = [
        'SenderID',
        'CustomerID',
        'Weight',
        'PackageType',
        'ShippingCost',
        'ReceiptDate',
    ];

    /**
     * Get the sender that owns the Package
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender(): BelongsTo
    {
        // Foreign key di tabel ini (packages), Owner key di tabel senders
        return $this->belongsTo(Sender::class, 'SenderID', 'SenderID');
    }

    /**
     * Get the customer that owns the Package
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    /**
     * Get all of the shipments for the Package
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'PackageID', 'PackageID');
    }
}