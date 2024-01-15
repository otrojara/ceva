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
        Schema::create('flujo_aprobacion_detalles', function (Blueprint $table) {
            $table->id();
            $table->integer('posicion');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('flujo_aprobacion_id');
           
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('flujo_aprobacion_id')->references('id')->on('flujo_aprobacions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flujo_aprobacion_detalles');
    }
};
