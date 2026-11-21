@extends('admin.layouts.app')

@section('title', 'Dash Images')

@section('content')
    <div class="page-header">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>Dash Images</h1>
        </div>
    </div>
    <div class="row" style="margin-top: 0;">

        @if (session('uploadMsg'))
            <div class="alert alert-success" style="color: green; margin-bottom: 15px; font-weight: bold;">
                {{ session('uploadMsg') }}
            </div>
        @endif

        @if (session('failedMsg'))
            <div class="alert alert-danger" style="color: red; margin-bottom: 15px; font-weight: bold;">
                {{ session('failedMsg') }}
            </div>
        @endif
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
            <section class="panel settings-panel" style="max-width: 420px; margin: 0;">
                <div class="panel-heading">
                    <h2>Upload Dashboard Image</h2>
                </div>


                <form action="{{ route('admin.uploadDashImg') }}" method="POST" enctype="multipart/form-data"
                    class="settings-form">
                    @csrf
                    <div class="form-grid" style="display: block;">
                        <div class="form-group" style="margin-bottom: 12px; width: 100%;">
                            <label for="title" style="font-weight: 600;">Title</label>
                            <input type="text" name="title" id="title" placeholder="Enter Image Title" required
                                style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; margin-top: 5px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 12px; width: 100%;">
                            <label for="image" style="font-weight: 600;">Choose Image</label>
                            <input type="file" name="image" id="image" required
                                style="width: 100%; margin-top: 5px;">
                            @error('image')
                                <span class="error-text" style="display: block; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 8px; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <section class="panel" style="margin-top: 24px;">
        <div class="panel-heading">
            <h2>Uploaded Images</h2>
        </div>
        <div class="table-wrap">
            <table class="table table-bordered table-striped admin-datatable">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse ($data as $image)
                        <tr>
                            <td>{{ $i }}</td>
                            <td style="text-align: center; vertical-align: middle; width: 100px;">
                                @if ($image->image)
                                    <img src="{{ asset('admin_assets/images/dash-img/' . $image->image) }}"
                                        alt="{{ $image->title }}"
                                        style="max-width: 80px; max-height: 80px; border-radius: 8px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td style="vertical-align: middle;">{{ $image->title }}</td>
                            <td style="vertical-align: middle;">
                                <span class="status {{ $image->status === 'Live' ? 'success' : 'warning' }}">
                                    {{ $image->status }}
                                </span>
                            </td>
                            <td style="vertical-align: middle;">
                                {{ $image->created_at ? $image->created_at->format('d M Y') : 'N/A' }}</td>
                            <td style="vertical-align: middle;">
                                <a href="{{ route('admin.dash-images.status-change', $image->id) }}"
                                    class="btn btn-outline"
                                    style="{{ $image->status === 'Live' ? 'background-color:red;color:white' : 'background-color:green;color:white' }}">
                                    {{ $image->status === 'Live' ? 'Non-Live' : 'Live' }}
                                </a>
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 18px;">No images found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
