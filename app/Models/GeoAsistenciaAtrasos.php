<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GeoAsistenciaAtrasos extends Model
{
    use HasFactory;

    public $table = "geo_asistencia_atrasos";


    protected $fillable = [
        'rut',
        'fecha',
        'delay',
        'workedhours',
        'absent',
        'holiday',
        'worked',
        'nonWorkedhours'

    ];



    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
}
