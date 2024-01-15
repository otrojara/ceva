<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FlujoAprobacion extends Model
{
    use HasFactory;

    public $table = "flujo_aprobacions";

    protected $fillable = [
        'nombre'
    ];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }

    public function roles_del_flujo(){
        return $this->hasMany('App\Models\BusinessUnit');
    }

}
