@extends('admin.layouts.app')

@section('title', 'Announcements')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Announcements</h1>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">Add Announcement</a>
    </div>

    <section class="panel">
        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        <tr>
                            <td>{{ $announcement->title }}</td>
                            <td><span class="status {{ $announcement->status === 'Active' ? 'success' : 'warning' }}">{{ $announcement->status }}</span></td>
                            <td>{{ $announcement->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-outline">Edit</a>
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" onclick="return confirm('Delete this announcement?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; padding: 18px;">No announcements found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $announcements->links() }}
    </section>
@endsection
