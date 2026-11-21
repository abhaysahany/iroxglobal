<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentDetail;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminDepositController extends Controller
{
    public function settings(): View
    {
        $title = 'Deposit Settings';
        $type = 'settings';
        $setting = Setting::first();

        return view('admin.deposits.table', compact('title', 'type', 'setting'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_bsc_wallet_address' => ['required', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'minimum_deposit_bnb' => ['required', 'numeric', 'min:0'],
            'maximum_deposit_bnb' => ['required', 'numeric', 'gte:minimum_deposit_bnb'],
            'deposit_gateway_state' => ['required', 'in:enabled,disabled'],
        ]);

        $setting = Setting::firstOrNew();
        $setting->fill($validated);
        $setting->save();

        return back()->with('success', 'Deposit settings updated successfully.');
    }

    public function index(): View
    {
        $paymentDetails = PaymentDetail::latest()->get();

        return view('admin.deposits.index', compact('paymentDetails'));
    }

    public function storePaymentDetails(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'wallet_address' => ['required', 'string', 'max:100'],
            'qr_code' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $directory = public_path('uploads/payment_qr');
        File::ensureDirectoryExists($directory);

        $file = $request->file('qr_code');
        $fileName = Str::uuid().'.'.$file->extension();
        $file->move($directory, $fileName);

        PaymentDetail::create([
            'address' => $data['wallet_address'],
            'qrcode' => 'uploads/payment_qr/'.$fileName,
            'status' => 'Live',
        ]);

        return back()->with('success', 'Deposit details submitted successfully.');
    }

    public function reports(): View
    {
        $title = 'Deposit Reports';
        $type = 'reports';
        $deposits = Deposit::query()
            ->select('member_id')
            ->selectRaw('SUM(amount) as total_deposit')
            ->selectRaw("SUM(CASE WHEN status = 'Approved' THEN amount ELSE 0 END) as approved_deposit")
            ->selectRaw("SUM(CASE WHEN status = 'Pending' THEN amount ELSE 0 END) as pending_deposit")
            ->selectRaw("SUM(CASE WHEN status = 'Rejected' THEN amount ELSE 0 END) as rejected_deposit")
            ->selectRaw('MAX(created_at) as last_deposit_date')
            ->with('member')
            ->groupBy('member_id')
            ->orderByDesc('last_deposit_date')
            ->paginate(10);

        return view('admin.deposits.table', compact('title', 'type', 'deposits'));
    }

    public function approve(Request $request, Deposit $deposit): RedirectResponse
    {
        $request->validate(['admin_remark' => ['nullable', 'string']]);

        if ($deposit->status === 'Approved') {
            return back()->with('error', 'Already approved.');
        }

        if ($deposit->status !== 'Pending') {
            return back()->with('error', 'Invalid request.');
        }

        DB::transaction(function () use ($deposit, $request) {
            $member = $deposit->member()->lockForUpdate()->first();
            $member->increment('wallet_balance', $deposit->amount);

            $deposit->update([
                'status' => 'Approved',
                'admin_remark' => $request->admin_remark,
            ]);
        });

        return back()->with('success', 'Approved successfully.');
    }

    public function reject(Request $request, Deposit $deposit): RedirectResponse
    {
        $request->validate(['admin_remark' => ['nullable', 'string']]);

        if ($deposit->status !== 'Pending') {
            return back()->with('error', 'Invalid request.');
        }

        $deposit->update([
            'status' => 'Rejected',
            'admin_remark' => $request->admin_remark,
        ]);

        return back()->with('success', 'Rejected successfully.');
    }
}
