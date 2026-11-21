<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class AdminLogDetail extends Model
{
    protected $table = 'admin_log_details';

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'updated_by', 'admin_id');
    }
}
