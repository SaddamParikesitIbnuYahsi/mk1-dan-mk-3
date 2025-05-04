<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id('ShipmentID'); // Menggunakan nama kolom PK dari diagram

            // Foreign Keys - Pastikan tabel packages, vendors, couriers sudah ada
            $table->foreignId('PackageID')->constrained('packages', 'PackageID')->onDelete('cascade'); // Jika paket dihapus, pengiriman ikut terhapus
            $table->foreignId('VendorID')->constrained('vendors', 'VendorID')->onDelete('restrict'); // Jangan hapus vendor jika masih ada pengiriman
            $table->foreignId('CourierID')->constrained('couriers', 'CourierID')->onDelete('restrict'); // Jangan hapus kurir jika masih ada pengiriman

            $table->string('TrackingNumber')->unique();
            $table->date('DeliveryDate')->nullable(); // Tanggal pengiriman aktual mungkin beda dari package
            $table->string('Status')->default('Pending'); // Contoh status awal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};