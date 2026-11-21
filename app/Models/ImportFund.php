<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Admin;

class ImportFund extends Model
{
    use HasFactory;

    /**
     * Limit admin-panel queries to the logged-in admin's records.
     * Skips console/queue contexts and superadmin/member routes.
     */
    protected static function booted()
    {
        if (app()->runningInConsole()) {
            return;
        }

        static::addGlobalScope('admin_ownership', function (Builder $builder) {
            $request = request();
            if (!$request) {
                return;
            }

            // leave non-admin areas untouched
            if (!$request->is('admin/*') || $request->is('superadmin/*')) {
                return;
            }

            if (session()->has('ADMIN_ID')) {
                $admin = Admin::find(session('ADMIN_ID'));
                if ($admin && $admin->admin_id) {
                    $adminId = $admin->admin_id;
                    // Keep user-originated rows visible; restrict only admin-added rows
                    $builder->where(function ($q) use ($adminId) {
                        $q->where('added_by', '!=', 'Admin')
                          ->orWhere('admin_id', $adminId);
                    });
                }
            }
        });
    }
}
