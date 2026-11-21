<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketText extends Model
{
    protected $table = 'ticket_texts';

    protected $fillable = [
        'ticket_id',
        'written_by',
        'text',
        'remark',
        'status',
    ];
}
