<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PackageDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_code',
        'package_name',
        'year_label',
        'registration_amount',
        'multiplier',
        'total_amount',
        'description',
        'status',
    ];

    public function memberDetails(): HasMany
    {
        return $this->hasMany(MemberDetail::class, 'package_detail_id');
    }
}
