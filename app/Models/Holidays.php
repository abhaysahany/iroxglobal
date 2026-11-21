<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
// use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{
    /**
     * Apply per-admin visibility for admin-panel routes.
     * Skips console/queue execution and superadmin area.
     */
    // protected static function booted()
    // {
    //     if (app()->runningInConsole()) {
    //         return;
    //     }

    //     static::addGlobalScope('admin_ownership', function (Builder $builder) {
    //         $request = request();
    //         if (!$request) {
    //             return;
    //         }

    //         // allow superadmin to view all
    //         if ($request->is('superadmin/*')) {
    //             return;
    //         }

    //         if ($request->is('admin/*') && session()->has('ADMIN_ID')) {
    //             $admin = Admin::find(session('ADMIN_ID'));
    //             if ($admin && !empty($admin->admin_id)) {
    //                 $builder->where('admin_id', $admin->admin_id);
    //             }
    //         }
    //     });
    // }
    
    use HasFactory;
}
