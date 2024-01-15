<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FlujoAprobacionDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'posicion',
        'user_id',
        'flujo_aprobacion_id'
    ];


    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }


    public function User(){
        return $this->belongsTo('App\Models\User','user_id');
    }
}
