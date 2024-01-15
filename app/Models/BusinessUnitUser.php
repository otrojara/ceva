<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BusinessUnitUser extends Model
{
    use HasFactory;

    public $table = "business_unit_user";

    protected $fillable = [
        'business_unit_id',
        'user_id'
    ];

    // public function fromDateTime($value)
    // {
    //     return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    // }
}
