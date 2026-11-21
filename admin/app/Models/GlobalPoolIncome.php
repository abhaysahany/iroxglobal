<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalPoolIncome extends Model
{
    protected $fillable = [
        'memberid',
        'pool_no',
        'amount',
        'total_income',
        'rate',
        'name',
        'activatingId',
        'status',
    ];
}
