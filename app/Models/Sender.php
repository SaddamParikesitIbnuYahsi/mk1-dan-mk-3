<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sender extends Model
{
    use HasFactory;

    protected $primaryKey = 'SenderID'; // Tentukan Primary Key jika bukan 'id'

    protected $fillable = [
        'Name',
        'Email',
        'PhoneNumber',
        'Address',
    ];

    /**
     * Get all of the packages for the Sender
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packages(): HasMany
    {
        // Foreign key di tabel packages, Local key di tabel senders
        return $this->hasMany(Package::class, 'SenderID', 'SenderID');
    }
}