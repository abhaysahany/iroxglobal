<?php

use App\Http\Controllers\Admin\AdminMemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



        Route::get('/investments', [AdminMemberController::class, 'index'])->name('investments.index');