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
        Schema::create('packages', function (Blueprint $table) {
            $table->id('PackageID'); // Menggunakan nama kolom PK dari diagram

            // Foreign Keys - Pastikan tabel senders & customers sudah ada
            $table->foreignId('SenderID')->constrained('senders', 'SenderID')->onDelete('restrict'); // Atau cascade/set null sesuai kebutuhan
            $table->foreignId('CustomerID')->constrained('customers', 'CustomerID')->onDelete('restrict'); // Atau cascade/set null

            $table->decimal('Weight', 8, 2)->nullable(); // Contoh: max 999999.99
            $table->string('PackageType')->nullable();
            $table->decimal('ShippingCost', 10, 2)->nullable(); // Contoh: max 99999999.99
            $table->date('ReceiptDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};