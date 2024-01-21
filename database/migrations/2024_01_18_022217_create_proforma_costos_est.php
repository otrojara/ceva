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
        Schema::create('proforma_costos_est', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->nullable();
            $table->integer('bu')->nullable();
            $table->string('region')->nullable();
            $table->string('site')->nullable();
            $table->string('dotacion')->nullable();
            $table->string('cargo')->nullable();
            $table->string('turno')->nullable();
            $table->string('tipo_turno')->nullable();
            $table->integer('costo_total')->nullable();
            $table->integer('fee')->nullable();
            $table->integer('neto')->nullable();
            $table->integer('gasto_variable')->nullable();
            $table->integer('gasto_fijo')->nullable();
            $table->integer('total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_costos_est');
    }
};
