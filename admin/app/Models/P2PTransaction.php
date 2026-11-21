<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class P2PTransaction extends Model
{
    protected $table = 'p2p_transactions';

    protected $fillable = ['sender_member_id', 'receiver_member_id', 'amount', 'transaction_note', 'status'];

    protected $casts = ['amount' => 'decimal:2'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'sender_member_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'receiver_member_id');
    }
}
