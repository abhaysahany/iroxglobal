<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RankIncome extends Model
{
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'memberid', 'memberid');
    }
}
