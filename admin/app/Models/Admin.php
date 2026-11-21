<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_image',
    ];

    protected $hidden = [
        'password',
    ];

    public function fundTransactions(): HasMany
    {
        return $this->hasMany(FundTransaction::class);
    }
}
