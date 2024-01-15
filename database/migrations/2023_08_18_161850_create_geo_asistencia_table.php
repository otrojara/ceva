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
        Schema::create('geo_asistencia', function (Blueprint $table) {
            $table->id();
            $table->string('rut');
            $table->string('date');
            $table->string('entrada_fecha')->nullable();
            $table->string('entrada_origin')->nullable();
            $table->string('entrada_groupdescription')->nullable();
            $table->string('salida_fecha')->nullable();
            $table->string('salida_origin')->nullable();
            $table->string('salida_groupdescription')->nullable();
            $table->string('type')->nullable();
            $table->string('shiftdisplay')->nullable();
            $table->string('delay')->nullable();
            $table->string('workedhours')->nullable();
            $table->string('absent')->nullable();
            $table->string('holiday')->nullable();
            $table->string('worked')->nullable();
            $table->string('nonworkedhours')->nullable();
            $table->string('to_description')->nullable();
            $table->string('to_star')->nullable();
            $table->string('to_ends')->nullable();
            $table->string('to_timeofftypedescription')->nullable();
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_asistencia');
    }
};
