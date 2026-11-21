<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        // Auth / Pre-existing fields
        'memberid',
        'sponsorid',
        'sponsor_name',
        'name',
        'email',
        'location',
        'mobile',
        'password',
        'txn_password',
        'status',
        'p2p_wallet',

        // Activation / Package page fields
        'member_id',
        'user_id',
        'member_name',
        'package_detail_id',
        'package_code',
        'package_name',
        'year_label',
        'registration_amount',
        'multiplier',
        'total_amount',
        'activation_status',
        'activated_at',
        'payment_status',
        'remarks',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
    ];

    public function packageDetail(): BelongsTo
    {
        return $this->belongsTo(PackageDetail::class, 'package_detail_id');
    }
}
