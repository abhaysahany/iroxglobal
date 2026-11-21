<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'email',
        'phone',
        'password',
        'sponsor_id',
        'status',
        'wallet_balance',
        'fund_wallet_balance',
        'is_active',
        'activated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'wallet_balance' => 'decimal:2',
        'fund_wallet_balance' => 'decimal:2',
    ];

    public function fundTransactions(): HasMany
    {
        return $this->hasMany(FundTransaction::class);
    }
}
