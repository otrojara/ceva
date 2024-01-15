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
        Schema::create('geo_asistencia_atrasos', function (Blueprint $table) {
            $table->id();
            $table->string('rut')->nullable();
            $table->string('fecha')->nullable();
            $table->string('delay')->nullable();
            $table->string('workedhours')->nullable();
            $table->string('absent')->nullable();
            $table->string('holiday')->nullable();
            $table->string('worked')->nullable();
            $table->string('nonWorkedhours')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_asistencia_atrasos');
    }
};
