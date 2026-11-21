<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::latest()->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $data['created_by'] = $request->session()->get('admin_id');

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Created successfully.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Updated successfully.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return back()->with('success', 'Deleted successfully.');
    }
}
