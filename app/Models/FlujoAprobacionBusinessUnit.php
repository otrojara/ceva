<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class FlujoAprobacionBusinessUnit extends Model
{
    use HasFactory;

    public $table = "flujo_aprobacion_business_unit";

    protected $fillable = [
        'flujo_aprobacion_id',
        'business_unit_id'
    ];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }


}
