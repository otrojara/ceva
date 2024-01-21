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
        Schema::create('rep_geoasistencia', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->string('dia')->nullable();
            $table->string('rut')->nullable();
            $table->string('nombre')->nullable();
            $table->string('estado')->nullable();
            $table->string('grupo')->nullable();
            $table->string('bu')->nullable();
            $table->string('cod_bu')->nullable();
            $table->string('cod_cargo')->nullable();
            $table->string('cargo')->nullable();
            $table->string('categoria')->nullable();
            $table->string('art22')->nullable();
            $table->string('empresa')->nullable();
            $table->string('turno')->nullable();
            $table->string('tipo_turno')->nullable();
            $table->string('inicio_contrato')->nullable();
            $table->string('termino_contrato')->nullable();
            $table->string('type')->nullable();
            $table->string('ausente')->nullable();
            $table->string('feriado')->nullable();
            $table->string('trabajado')->nullable();
            $table->string('ingreso')->nullable();
            $table->string('ingreso_origen')->nullable();
            $table->string('ingreso_grupo')->nullable();
            $table->string('salida')->nullable();
            $table->string('salida_origen')->nullable();
            $table->string('salida_grupo')->nullable();
            $table->string('atraso')->nullable();
            $table->string('horas_extras')->nullable();
            $table->string('permiso')->nullable();
            $table->string('to_star')->nullable();
            $table->string('to_end')->nullable();
            $table->integer('presente')->nullable();
            $table->integer('libre')->nullable();
            $table->integer('licencia')->nullable();
            $table->integer('cumpleanio')->nullable();
            $table->integer('administrativo')->nullable();
            $table->integer('vacaciones')->nullable();
            $table->integer('permiso_con_goce')->nullable();
            $table->integer('no_presente')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rep_geoasistencia');
    }
};
