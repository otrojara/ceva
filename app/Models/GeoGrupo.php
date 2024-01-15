<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoGrupo extends Model
{
    use HasFactory;

    public $table = "geo_grupos";

    protected $fillable = [
        'nivel_uno',
        'nivel_dos',
        'nivel_tres'
    ];
}
