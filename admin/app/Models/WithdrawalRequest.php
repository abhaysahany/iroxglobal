<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends Model
{
    protected $table = 'withdrawal_requests';

    protected $fillable = [
        'request_date',
        'payment_date',
        'request_id',
        'batch_withdrawal_id',
        'txnid',
        'memberid',
        'masterid',
        'name',
        'wallet_address',
        'type',
        'gross_amount',
        'service_charge',
        'net_amount',
        'status',
        'utr',
        'other_detail',
        'image',
        'reason',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'payment_date' => 'datetime',
        'gross_amount' => 'decimal:4',
        'service_charge' => 'decimal:4',
        'net_amount' => 'decimal:4',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }
}
