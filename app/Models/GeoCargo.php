<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoCargo extends Model
{
    use HasFactory;

    public $table = "geo_cargo";

    protected $fillable = [
        'cargo',
        'categoria'
    ];
}
