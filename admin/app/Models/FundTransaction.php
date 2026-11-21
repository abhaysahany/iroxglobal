<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundTransaction extends Model
{
    protected $fillable = [
        'member_id',
        'admin_id',
        'type',
        'amount',
        'wallet_type',
        'remark',
        'balance_before',
        'balance_after',
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
