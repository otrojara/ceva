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
        Schema::create('flujo_aprobacion_business_unit', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('flujo_aprobacion_id');
            $table->unsignedBigInteger('business_unit_id')->unique();

            $table->foreign('flujo_aprobacion_id')->references('id')->on('flujo_aprobacions')->onDelete('cascade');
            $table->foreign('business_unit_id')->references('id')->on('business_units')->onDelete('cascade');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flujo_aprobacion_business_unit');
        Schema::dropIfExists('business_unit_user');
        Schema::dropIfExists('users');
        Schema::dropIfExists('flujo_aprobacions');
        Schema::dropIfExists('business_units');
        Schema::dropIfExists('sis_comunas');
        Schema::dropIfExists('sis_regions');
    }
};
