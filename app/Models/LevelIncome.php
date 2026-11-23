<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelIncome extends Model
{
    use HasFactory;

    protected $table = 'level_incomes';

    protected $casts = [
        'amount' => 'decimal:5',
        'staking_income' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }

    public function fromMember(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'level_id', 'memberid');
    }
}

