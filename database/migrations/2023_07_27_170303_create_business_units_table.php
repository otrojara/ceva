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
        Schema::create('business_units', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo');
            $table->string('nombre');
            $table->string('grupo_geovictoria');
            $table->string('gerente_operacion');
            $table->string('contract_id');
            $table->unsignedBigInteger('comuna_id')->nullable();
            $table->string('zona');

            $table->foreign('comuna_id')->references('id')->on('sis_comunas')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
