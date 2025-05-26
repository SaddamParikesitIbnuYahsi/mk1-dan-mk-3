<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
            $table->string('LicenseNumber')->unique();
            $table->string('VehicleType');
            $table->string('PhoneNumber');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('couriers');
    }    
};
