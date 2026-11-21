<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    /**
     * Show the settings form.
     */
    public function index(): View
    {
        $setting = Setting::first();

        return view('admin.global-count', compact('setting'));
    }

    /**
     * Create the first settings row or update the existing one.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'set_weekly_count' => 'required',
        ]);

        $setting = Setting::firstOrNew();
        $oldLogo = $setting->website_logo;
        $set_days = $validated['set_weekly_count'] ?? null;

        $setting->fill($validated);
        $setting->save();

        return back()->with('success', 'Count updated successfully.');
    }
}
