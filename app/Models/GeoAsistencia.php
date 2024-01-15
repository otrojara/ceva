<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GeoAsistencia extends Model
{
    use HasFactory;

    public $table = "geo_asistencia";


    protected $fillable = [
        'rut',
        'date',
        'shiftpunchtype',
        'entrada_fecha',
        'entrada_origin',
        'entrada_groupdescription',
        'salida_fecha',
        'salida_origin',
        'salida_groupdescription',
        'type',
        'shiftdisplay',
        'tipo_turno',
        'delay',
        'assignedextratime',
        'workedhours',
        'absent',
        'holiday',
        'worked',
        'nonworkedhours',
        'to_description',
        'to_star',
        'to_ends',
        'to_timeofftypedescription'
    ];



    // public function fromDateTime($value)
    // {
    //     return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    // }
}
