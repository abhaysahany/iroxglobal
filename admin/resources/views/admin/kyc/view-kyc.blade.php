@extends('admin.layouts.app')

@section('title', 'View KYC Details')

@section('content')
    <style>
        .kyc-form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        @media (max-width: 992px) {
            .kyc-form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .kyc-form-grid {
                grid-template-columns: 1fr;
            }
        }

        .kyc-image-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-top: 24px;
        }

        @media (max-width: 768px) {
            .kyc-image-grid {
                grid-template-columns: 1fr;
            }
        }

        .kyc-img-card {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
        }

        body.theme-dark .kyc-img-card {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.05);
        }

        .kyc-img-container {
            width: 100%;
            height: 240px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        body.theme-dark .kyc-img-container {
            background: #0f172a;
            border-color: rgba(255, 255, 255, 0.05);
        }

        .kyc-img-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .kyc-img-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .kyc-img-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--gl-text);
        }
    </style>

    <div class="page-header" style="margin-bottom: 20px; padding-bottom: 0;">
        <div>
            <p class="page-kicker">Admin Panel</p>
            <h1>KYC Details</h1>
        </div>
    </div>

    @if (session()->has('successMsg'))
        <div class="alert alert-success" style="color: green; font-weight: bold; margin-bottom: 20px;">
            {{ session('successMsg') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger" style="color: red; font-weight: bold; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('kycMesg'))
        <div class="alert alert-info" style="color: blue; font-weight: bold; margin-bottom: 20px;">
            {{ session('kycMesg') }}
        </div>
    @endif

    <!-- DETAILS & BANK FORM -->
    <section class="panel">
        <div class="panel-heading"
            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <h2>KYC Details (MemberID : {{ $mdata['memberid'] }})</h2>
            <div style="display: flex; gap: 10px;">
                @if ($mdata['kyc_status'] == 'Verified')
                    <span class="status success"
                        style="padding: 6px 16px; font-size: 13px; font-weight: 700;">Verified</span>
                @else
                    <a href="{{ url('admin/kyc/verify') }}/{{ $mdata['id'] }}" class="btn btn-primary"
                        style="padding: 8px 16px; font-size: 13px; font-weight: 700;">Verify KYC</a>
                    <a href="{{ url('admin/kyc/reject') }}/{{ $mdata['id'] }}" class="btn btn-danger"
                        style="padding: 8px 16px; font-size: 13px; font-weight: 700; background: #ef4444; border-color: #ef4444;">Reject
                        KYC</a>
                @endif
            </div>
        </div>

        <div class="panel-body" style="padding: 24px;">
            <form action="{{ route('admin.updateKYC') }}" method="POST">
                @csrf
                <div class="kyc-form-grid" style="display:block !important;">
                    {{--                     
                    <div class="form-group">
                        <label style="font-weight: 700; margin-bottom: 6px; display: block;">PAN No</label>
                        <input type="text" class="form-control" name="pan_no" value="{{ $mdata['pan_no'] }}" style="width: 100%;">
                    </div> --}}
                    <div class="form-group">
                        <label style="font-weight: 700; margin-bottom: 6px; display: block;">Aadhar No</label>
                        <input type="text" class="form-control" name="aadhar_no" value="{{ $mdata['aadhar_no'] }}"
                            style="width: 100%;">
                    </div>
                </div>

                <div style="display: flex; justify-content: center; margin-top: 15px;">
                    <input type="hidden" name="id" value="{{ $mdata['id'] }}">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Update Details</button>
                </div>
            </form>
        </div>
    </section>

    <!-- UPLOADED IMAGES -->
    <div class="kyc-image-grid">
        <!-- 1. Selfie Image (New) -->
        <div class="kyc-img-card">
            <div class="kyc-img-container">
                @if ($mdata['selfie_image'] != '')
                    <img src="{{ config('detailsApp.app_url') . '/uploads/' . $mdata['selfie_image'] }}"
                        alt="Selfie Image">
                @else
                    <img src="{{ asset('uploads/avatar.jpg') }}" alt="No Selfie Image" style="opacity: 0.5;">
                @endif
            </div>
            <div class="kyc-img-body">
                <div class="kyc-img-title">Selfie Image</div>
                <form action="{{ route('admin.updateSelfie') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" style="margin-bottom: 12px;">
                        <input type="file" name="selfie_image" class="form-control" required
                            style="width: 100%; padding: 6px;">
                        @error('selfie_image')
                            <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="id" value="{{ $mdata['id'] }}">
                    <input type="hidden" name="oldImage3" value="{{ $mdata['selfie_image'] }}">
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 6px 16px; font-size: 13px;">Update
                            Image</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Aadhar Front Image -->
        <div class="kyc-img-card">
            <div class="kyc-img-container">
                @if ($mdata['front_image'] != '')
                    <img src="{{ config('detailsApp.app_url') . '/uploads/' . $mdata['front_image'] }}"
                        alt="Aadhar Front Image">
                @else
                    <img src="{{ asset('uploads/avatar.jpg') }}" alt="No Aadhar Front" style="opacity: 0.5;">
                @endif
            </div>
            <div class="kyc-img-body">
                <div class="kyc-img-title">Aadhar Front Image</div>
                <form action="{{ route('admin.updateAfront') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" style="margin-bottom: 12px;">
                        <input type="file" name="front_image" class="form-control" required
                            style="width: 100%; padding: 6px;">
                        @error('front_image')
                            <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="id" value="{{ $mdata['id'] }}">
                    <input type="hidden" name="oldImage1" value="{{ $mdata['front_image'] }}">
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 6px 16px; font-size: 13px;">Update
                            Image</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Aadhar Back Image -->
        <div class="kyc-img-card">
            <div class="kyc-img-container">
                @if ($mdata['back_image'] != '')
                    <img src="{{ config('detailsApp.app_url') . '/uploads/' . $mdata['back_image'] }}"
                        alt="Aadhar Back Image">
                @else
                    <img src="{{ asset('uploads/avatar.jpg') }}" alt="No Aadhar Back" style="opacity: 0.5;">
                @endif
            </div>
            <div class="kyc-img-body">
                <div class="kyc-img-title">Aadhar Back Image</div>
                <form action="{{ route('admin.updateAback') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" style="margin-bottom: 12px;">
                        <input type="file" name="back_image" class="form-control" required
                            style="width: 100%; padding: 6px;">
                        @error('back_image')
                            <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="id" value="{{ $mdata['id'] }}">
                    <input type="hidden" name="oldImage2" value="{{ $mdata['back_image'] }}">
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 6px 16px; font-size: 13px;">Update
                            Image</button>
                    </div>
                </form>
            </div>
        </div>



    </div>
@endsection
