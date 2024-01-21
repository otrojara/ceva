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
        Schema::create('proforma_mo_detalle', function (Blueprint $table) {
            $table->id();
            $table->string('rut')->nullable();
            $table->string('nombre')->nullable();
            $table->string('inicio_contrato')->nullable();
            $table->string('periodo')->nullable();
            $table->integer('cod_bu')->nullable();
            $table->string('empresa')->nullable();
            $table->string('cargo')->nullable();
            $table->string('tipo_turno')->nullable();
            $table->integer('total')->nullable();
            $table->string('categoria')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('comentarios')->nullable();
            $table->integer('costo_empresa_30')->nullable();
            $table->integer('costo_total')->nullable();
            $table->integer('provision_beneficios')->nullable();
            $table->integer('costo_total_productivo')->nullable();
            $table->decimal('dias_administrativos',20,15)->nullable();
            $table->decimal('dias_cumple',20,15)->nullable();
            $table->decimal('total_dias_productivos',20,15)->nullable();
            $table->decimal('dias_totales',20,15)->nullable();
            $table->integer('costo_dias_adm_cum')->nullable();
            $table->integer('costo_total_final')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_mo_detalle');
    }
};
