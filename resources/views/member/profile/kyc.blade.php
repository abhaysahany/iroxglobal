@extends('member.layouts.app')

@section('title', 'KYC Verification - ' . config('detailsApp.name'))
@section('body_class', 'member-dashboard-body')
@section('page_title', 'KYC Verification')

@section('content')
    <style>
        .kyc-layout {
            display: flex;
            flex-direction: column;
            gap: 24px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .kyc-status-card {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 24px;
            border-radius: 20px;
        }

        .kyc-status-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            font-size: 28px;
        }

        /* Status Colors */
        .kyc-status-card.not-submitted {
            background: rgba(100, 116, 139, 0.08);
            border: 1px solid rgba(100, 116, 139, 0.15);
        }

        .kyc-status-card.not-submitted .kyc-status-icon {
            background: rgba(100, 116, 139, 0.12);
            color: #64748b;
        }

        .kyc-status-card.pending {
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.15);
        }

        .kyc-status-card.pending .kyc-status-icon {
            background: rgba(245, 158, 11, 0.12);
            color: #f59e0b;
        }

        .kyc-status-card.approved {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.15);
        }

        .kyc-status-card.approved .kyc-status-icon {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
        }

        .kyc-status-card.rejected {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
        }

        .kyc-status-card.rejected .kyc-status-icon {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }

        .kyc-status-info h3 {
            margin: 0 0 4px 0;
            font-size: 18px;
            font-weight: 700;
        }

        .kyc-status-info p {
            margin: 0;
            font-size: 14px;
            color: var(--gl-muted);
            font-weight: 500;
        }

        .kyc-form-card {
            padding: 30px;
            border-radius: 24px;
        }

        .kyc-form-card h2 {
            margin: 0 0 24px 0;
            font-size: 20px;
            font-weight: 800;
        }

        .kyc-form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            .kyc-form-grid {
                grid-template-columns: 1fr;
            }
        }

        .kyc-form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .kyc-form-group.full-width {
            grid-column: span 2;
        }

        @media (max-width: 768px) {
            .kyc-form-group.full-width {
                grid-column: span 1;
            }
        }

        .kyc-form-group label {
            font-size: 14px;
            font-weight: 700;
            color: var(--gl-text);
        }

        .kyc-input,
        .kyc-select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-weight: 600;
            color: var(--gl-text);
            transition: all 0.25s;
        }

        body.theme-dark .kyc-input,
        body.theme-dark .kyc-select {
            background: rgba(15, 23, 42, 0.6);
            border-color: rgba(255, 255, 255, 0.08);
        }

        .kyc-input:focus,
        .kyc-select:focus {
            outline: none;
            border-color: var(--gl-green);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Upload Box Design */
        .kyc-upload-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            .kyc-upload-row {
                grid-template-columns: 1fr;
            }
        }

        .kyc-upload-box {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 180px;
            border: 2px dashed rgba(0, 0, 0, 0.15);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            padding: 20px;
            text-align: center;
            transition: all 0.25s;
            overflow: hidden;
        }

        body.theme-dark .kyc-upload-box {
            background: rgba(15, 23, 42, 0.3);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .kyc-upload-box:hover {
            border-color: var(--gl-green);
            background: rgba(255, 255, 255, 0.6);
        }

        body.theme-dark .kyc-upload-box:hover {
            background: rgba(15, 23, 42, 0.4);
        }

        .kyc-file-input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .kyc-upload-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            pointer-events: none;
        }

        .kyc-upload-placeholder i {
            font-size: 32px;
            color: var(--gl-muted);
            transition: color 0.2s;
        }

        .kyc-upload-box:hover .kyc-upload-placeholder i {
            color: var(--gl-green);
        }

        .kyc-upload-placeholder span {
            font-size: 14px;
            font-weight: 700;
        }

        .kyc-upload-placeholder small {
            font-size: 12px;
            color: var(--gl-muted);
        }

        .kyc-image-preview {
            width: 100%;
            max-height: 160px;
            object-fit: contain;
            border-radius: 8px;
            z-index: 1;
        }

        .kyc-submit-btn {
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            border: 0;
            background: var(--gl-green);
            color: white;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .kyc-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);
        }

        /* Details View */
        .kyc-details-card {
            padding: 24px;
            border-radius: 20px;
        }

        @media (max-width: 768px) {
            .kyc-status-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 20px;
            }
        }

        .kyc-details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        @media (max-width: 768px) {
            .kyc-details-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }

        .kyc-detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .kyc-detail-item span {
            font-size: 13px;
            color: var(--gl-muted);
            font-weight: 600;
        }

        .kyc-detail-item strong {
            font-size: 15px;
            font-weight: 700;
        }

        .kyc-doc-previews {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 24px;
        }

        @media (max-width: 768px) {
            .kyc-doc-previews {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        .kyc-doc-preview-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .kyc-doc-preview-item img {
            width: 100%;
            max-height: 220px;
            object-fit: contain;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: rgba(0, 0, 0, 0.02);
            padding: 8px;
        }
    </style>

    <main class="member-page dashboard-page">
        <div class="dashboard-backdrop"></div>
        @include('member.layouts.sidebar')

        <section class="dashboard-shell">
            @include('member.layouts.header')

            <div class="dashboard-content">
                @if (session('successMsg'))
                    <div class="glass-alert success dashboard-alert">{{ session('successMsg') }}</div>
                @endif

                @if (session('failedMsg'))
                    <div class="glass-alert danger dashboard-alert">{{ session('failedMsg') }}</div>
                @endif

                <div class="kyc-layout">

                    <!-- 1. STATUS CARD -->
                    @if ($data['kyc_status'] == 'Unverified')
                        <article class="kyc-status-card not-submitted">
                            <div class="kyc-status-icon">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <div class="kyc-status-info">
                                <h3>KYC Not Submitted</h3>
                                <p>Please upload your government-issued identity documents below to verify your account.</p>
                            </div>
                        </article>
                    @elseif($data['kyc_status'] == 'Submitted')
                        <article class="kyc-status-card pending">
                            <div class="kyc-status-icon">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div class="kyc-status-info">
                                <h3>KYC Verification Pending</h3>
                                <p>Your documents are currently under review. This process usually takes 24 to 48 hours.</p>
                            </div>
                        </article>
                    @elseif($data['kyc_status'] == 'Verified')
                        <article class="kyc-status-card approved">
                            <div class="kyc-status-icon">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div class="kyc-status-info">
                                <h3>KYC Verified</h3>
                                <p>Congratulations! Your account has been fully verified and is in good standing.</p>
                            </div>
                        </article>
                    @elseif($data['kyc_status'] == 'Rejected')
                        <article class="kyc-status-card rejected">
                            <div class="kyc-status-icon">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </div>
                            <div class="kyc-status-info">
                                <h3>KYC Verification Rejected</h3>
                                <p style="color: #ef4444; font-weight: 700; margin-bottom: 4px;">Reason:
                                    {{ $kycData->reject_reason }}</p>
                                <p>Please review the reason, correct your details, and resubmit the form below.</p>
                            </div>
                        </article>
                    @endif

                    <!-- 2. KYC SUBMISSION FORM (Show if not submitted or rejected) -->
                    @if ($data['kyc_status'] == 'Unverified' || $data['kyc_status'] == 'Rejected')
                        <article class="kyc-form-card glass-surface">
                            <h2>Submit KYC Documents</h2>
                            <form action="{{ route('member.updateKyc') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="kyc-form-grid" style="display: block !important;">
                                    <div class="kyc-form-group">
                                        <label for="Aadhar">Aadhar No.</label>
                                        <input type="text" name="aadhar_no" class="kyc-input"
                                            placeholder="Enter Aadhar Card No.">
                                        @error('aadhar_no')
                                            <span class=" text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- <div class="kyc-form-group">
                                        <label for="PAN">PAN No.</label>
                                        <input type="text" name="pan_no" class="kyc-input" placeholder="Enter PAN No">
                                        @error('pan_no')
                                            <span class=" text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}
                                </div>

                                <div class="kyc-upload-row">
                                    <div class="kyc-form-group">
                                        <label>Aadhar Front Image</label>
                                        <div class="kyc-upload-box">
                                            <input type="file" name="front_image" id="front_image" class="kyc-file-input"
                                                required accept="image/*">
                                            <div class="kyc-upload-placeholder" id="front_placeholder">
                                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                                <span>Upload Front Side</span>
                                                <small>PNG, JPG, JPEG up to 2MB</small>
                                            </div>
                                            <img id="front_preview" class="kyc-image-preview" style="display: none;"
                                                alt="Front Preview" />
                                        </div>
                                        @error('front_image')
                                            <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="kyc-form-group">
                                        <label>Aadhar Back Image</label>
                                        <div class="kyc-upload-box">
                                            <input type="file" name="back_image" id="back_image" class="kyc-file-input"
                                                required accept="image/*">
                                            <div class="kyc-upload-placeholder" id="back_placeholder">
                                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                                <span>Upload Back Side</span>
                                                <small>PNG, JPG, JPEG up to 2MB</small>
                                            </div>
                                            <img id="back_preview" class="kyc-image-preview" style="display: none;"
                                                alt="Back Preview" />
                                        </div>
                                        @error('back_image')
                                            <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="kyc-form-group" style="display: block !important;">
                                        <label>Selfie Image</label>
                                        <div class="kyc-upload-box">
                                            <input type="file" name="selfie_image" id="selfie_image"
                                                class="kyc-file-input" required accept="image/*">
                                            <div class="kyc-upload-placeholder" id="selfie_placeholder">
                                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                                <span>Upload Selfie Image</span>
                                                <small>PNG, JPG, JPEG up to 2MB</small>
                                            </div>
                                            <img id="selfie_preview" class="kyc-image-preview" style="display: none;"
                                                alt="Selfie Preview" />
                                        </div>
                                        @error('selfie_image')
                                            <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: center; margin-top: 12px;">
                                    <button type="submit" class="kyc-submit-btn">Submit KYC</button>
                                </div>
                            </form>
                        </article>
                    @endif

                    <!-- 3. SUBMITTED DETAILS VIEW (Show if pending or approved) -->
                    @if ($data && ($data->kyc_status == 'Submitted' || $data->kyc_status == 'Verified'))
                        <article class="kyc-details-card glass-surface" style="border-radius: 16px; padding: 24px; margin-bottom: 24px;">
                            <h2 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 800;">Submitted KYC Details</h2>
                            
                            <div class="kyc-details-grid" style="margin-bottom: 24px;">
                                <div class="kyc-detail-item" style="display: flex; flex-direction: column; gap: 4px;">
                                    <span style="font-size: 13px; color: var(--gl-muted); font-weight: 600;">Aadhar Number</span>
                                    <strong style="font-size: 15px; font-weight: 700;">{{ $data->aadhar_no }}</strong>
                                </div>
                                <div class="kyc-detail-item" style="display: flex; flex-direction: column; gap: 4px;">
                                    <span style="font-size: 13px; color: var(--gl-muted); font-weight: 600;">Verification Status</span>
                                    <strong style="font-size: 15px; font-weight: 700; color: {{ $data->kyc_status == 'Verified' ? '#10b981' : '#f59e0b' }}">{{ $data->kyc_status }}</strong>
                                </div>
                                <div class="kyc-detail-item" style="display: flex; flex-direction: column; gap: 4px;">
                                    <span style="font-size: 13px; color: var(--gl-muted); font-weight: 600;">Submitted Date</span>
                                    <strong
                                        style="font-size: 15px; font-weight: 700;">{{ $data->kyc_date ? date('d M Y h:i A', strtotime($data->kyc_date)) : 'N/A' }}</strong>
                                </div>
                            </div>

                            <div class="kyc-doc-previews">
                                <div class="kyc-doc-preview-item" style="display: flex; flex-direction: column; gap: 8px;">
                                    <span style="font-size: 13px; color: var(--gl-muted); font-weight: 600;">Aadhar Front</span>
                                    <img src="{{ asset('uploads/' . $data->front_image) }}" alt="Aadhar Front" style="width: 100%; max-height: 180px; object-fit: contain; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); background: rgba(0,0,0,0.02); padding: 5px;">
                                </div>
                                <div class="kyc-doc-preview-item" style="display: flex; flex-direction: column; gap: 8px;">
                                    <span style="font-size: 13px; color: var(--gl-muted); font-weight: 600;">Aadhar Back</span>
                                    <img src="{{ asset('uploads/' . $data->back_image) }}" alt="Aadhar Back" style="width: 100%; max-height: 180px; object-fit: contain; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); background: rgba(0,0,0,0.02); padding: 5px;">
                                </div>
                                <div class="kyc-doc-preview-item" style="display: flex; flex-direction: column; gap: 8px;">
                                    <span style="font-size: 13px; color: var(--gl-muted); font-weight: 600;">Selfie</span>
                                    <img src="{{ asset('uploads/' . $data->selfie_image) }}" alt="Selfie" style="width: 100%; max-height: 180px; object-fit: contain; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); background: rgba(0,0,0,0.02); padding: 5px;">
                                </div>
                            </div>
                        </article>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Live Preview for Front Image
            var frontInput = document.getElementById('front_image');
            if (frontInput) {
                frontInput.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            var img = document.getElementById('front_preview');
                            var placeholder = document.getElementById('front_placeholder');
                            if (img && placeholder) {
                                img.src = event.target.result;
                                img.style.display = 'block';
                                placeholder.style.display = 'none';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Live Preview for Back Image
            var backInput = document.getElementById('back_image');
            if (backInput) {
                backInput.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            var img = document.getElementById('back_preview');
                            var placeholder = document.getElementById('back_placeholder');
                            if (img && placeholder) {
                                img.src = event.target.result;
                                img.style.display = 'block';
                                placeholder.style.display = 'none';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Live Preview for Selfie Image
            var selfieInput = document.getElementById('selfie_image');
            if (selfieInput) {
                selfieInput.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            var img = document.getElementById('selfie_preview');
                            var placeholder = document.getElementById('selfie_placeholder');
                            if (img && placeholder) {
                                img.src = event.target.result;
                                img.style.display = 'block';
                                placeholder.style.display = 'none';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endsection
