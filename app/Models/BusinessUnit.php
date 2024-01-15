<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'codigo',
        'nombre',
        'grupo_geovictoria',
        'comuna_id',
        'zona',
        'gerente_operacion',
        'contract_id'
    ];

    // public function fromDateTime($value)
    // {
    //     return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    // }

    public function User(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    //Relacion muchos a muchos 
    public function flujo(){
        return $this->belongsTo('App\Models\FlujoAprobacion');
    }


}
