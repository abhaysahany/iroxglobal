<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function index(Request $request): View
    {
        $admin = Admin::find($request->session()->get('admin_id'));

        return view('admin.profile.index', compact('admin'));
    }

    public function update(Request $request): RedirectResponse
    {
        $admin = Admin::find($request->session()->get('admin_id'));

        if (! $admin) {
            return back()->with('error', 'Invalid request.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        unset($data['profile_image']);
       

        if ($request->hasFile('profile_image')) {
            File::ensureDirectoryExists(public_path('uploads/admins'));
            $imageName = 'admin_' . $admin->id . '_' . time() . '_' . uniqid() . '.' . strtolower($request->file('profile_image')->getClientOriginalExtension());
            $request->file('profile_image')->move(public_path('uploads/admins'), $imageName);

            if ($admin->profile_image && File::exists(public_path($admin->profile_image))) {
                File::delete(public_path($admin->profile_image));
            }

            $data['profile_image'] = 'uploads/admins/' . $imageName;
        }

        $admin->update($data);

        $request->session()->put([
            'admin_name' => $data['name'],
            'admin_email' => $data['email'],
        ]);

        return back()->with('success', 'Updated successfully.');
    }
}
