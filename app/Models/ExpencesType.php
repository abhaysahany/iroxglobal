<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Admin;

class ExpencesType extends Model
{
    /**
     * Restrict admin-panel visibility to the logged-in admin's own expense rows.
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

            // Skip scoping outside admin area or inside superadmin
            if (!$request->is('admin/*') || $request->is('superadmin/*')) {
                return;
            }

            if (session()->has('ADMIN_ID')) {
                $admin = Admin::find(session('ADMIN_ID'));
                if ($admin && $admin->admin_id) {
                    $builder->where('admin_id', $admin->admin_id);
                }
            }
        });
    }
}
