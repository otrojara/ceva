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
        Schema::create('rrhh_libro', function (Blueprint $table) {
            $table->id();
            $table->string('rut')->nullable();
            $table->string('nombre')->nullable();
            $table->string('mes_proceso')->nullable();
            $table->string('fecha_ingreso')->nullable();
            $table->string('fecha_retiro')->nullable();
            $table->char('sexo')->nullable();
            $table->string('fecha_nacimiento')->nullable();
            $table->string('codigo_c_costo')->nullable();
            $table->integer('centro_costo')->nullable();
            $table->integer('tipos_contrato')->nullable();
            $table->integer('dias_trabajados')->nullable();
            $table->integer('dias_de_ausentismo')->nullable();
            $table->integer('total_haberes_afectos')->nullable();
            $table->integer('total_descuentos_personales')->nullable();
            $table->integer('total_aportes_legales')->nullable();
            $table->integer('liquido_del_mes')->nullable();
            $table->integer('subase')->nullable();
            $table->integer('atraso')->nullable();
            $table->integer('dessal')->nullable();
            $table->integer('gratif')->nullable();
            $table->integer('bnoass')->nullable();
            $table->integer('hex60')->nullable();
            $table->integer('difsue')->nullable();
            $table->integer('bonoch')->nullable();
            $table->integer('hex075')->nullable();
            $table->integer('comfes')->nullable();
            $table->integer('bonree')->nullable();
            $table->integer('boninv')->nullable();
            $table->integer('boexac')->nullable();
            $table->integer('bonant')->nullable();
            $table->integer('btutor')->nullable();
            $table->integer('bonmaq')->nullable();
            $table->integer('boescl')->nullable();
            $table->integer('boesup')->nullable();
            $table->integer('bonimp')->nullable();
            $table->integer('bomatr')->nullable();
            $table->integer('boncom')->nullable();
            $table->integer('difmov')->nullable();
            $table->integer('movili')->nullable();
            $table->integer('colaci')->nullable();
            $table->integer('difcol')->nullable();
            $table->integer('colsid')->nullable();
            $table->integer('sbgiro')->nullable();
            $table->integer('adimov')->nullable();
            $table->integer('asacum')->nullable();
            $table->integer('asfama')->nullable();
            $table->integer('lsanna')->nullable();
            $table->integer('mutual')->nullable();
            $table->integer('segcee')->nullable();
            $table->integer('segcei')->nullable();
            $table->integer('sisafp')->nullable();
            $table->integer('total_haberes_imponibles')->nullable();
            $table->integer('total_haberes_no_imponibles')->nullable();
            $table->integer('total_haberes_costo_libro')->nullable();
            $table->integer('aportes_patronales')->nullable();
            $table->integer('costo_libro')->nullable();
            $table->integer('bono_gestion')->nullable();
            $table->integer('total_finiquitos')->nullable();
            $table->integer('total_vacaciones')->nullable();
            $table->integer('prov_aguinaldo')->nullable();
            $table->integer('prov_beneficios_eventos')->nullable();
            $table->integer('sistemas_cpeople')->nullable();
            $table->integer('prima_seguro_salud')->nullable();
            $table->integer('sistemas_cas')->nullable();
            $table->integer('alto_check')->nullable();
            $table->integer('global_insurance')->nullable();
            $table->integer('infocontrol')->nullable();
            $table->integer('sistemas_payroll_adp')->nullable();
            $table->integer('dias_administrativos')->nullable();
            $table->integer('dia_cumpleanos')->nullable();
            $table->integer('tarjeta_de_cafe')->nullable();
            $table->integer('sala_cuna')->nullable();
            $table->integer('total_beneficios')->nullable();
            $table->integer('sueldo_base')->nullable();
            $table->integer('total_haberes')->nullable();
            $table->integer('costo_libro_rem')->nullable();
            $table->integer('prov_y_benefios')->nullable();
            $table->integer('costo_empresa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhh_libro');
    }
};
