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
        Schema::create('rep_geoerrores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('rut')->nullable();
            $table->string('bu')->nullable();
            $table->string('fecha')->nullable();
            $table->integer('sin_cargo')->nullable();
            $table->integer('sin_turno')->nullable();
            $table->integer('sin_inicio_contrato')->nullable();
            $table->integer('sin_fin_contrato')->nullable();
            $table->integer('sin_salida')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rep_geoerrores');
    }
};
