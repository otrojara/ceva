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
        Schema::create('geo_trabajadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('rut')->nullable();
            $table->string('cod_Cargo')->nullable();
            $table->string('grupo')->nullable();
            $table->string('bu')->nullable();
            $table->string('cod_bu')->nullable();
            $table->date('fecha')->nullable();
            $table->string('email')->nullable();
            $table->string('turno')->nullable();
            $table->string('cargo')->nullable();
            $table->string('categoria')->nullable();
            $table->string('ART22')->nullable();
            $table->string('inicio_contrato')->nullable();
            $table->string('fin_contrato')->nullable();
            $table->string('empresa')->nullable();
            $table->string('enabled')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_trabajadores');
    }
};
