<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function newWithdrawalRequests(): View
    {
        $withdrawals = WithdrawalRequest::with('member')
            ->where('status', 'Pending')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        return view('admin.payment.index', [
            'page' => 'new',
            'title' => 'New Withdrawal Requests',
            'subtitle' => 'All New Withdrawal Requests',
            'withdrawals' => $withdrawals,
        ]);
    }

    public function cancelledRequests(): View
    {
        $withdrawals = WithdrawalRequest::with('member')
            ->whereIn('status', ['Rejected', 'Cancelled', 'Canceled'])
            ->latest('updated_at')
            ->paginate(15);

        return view('admin.payment.index', [
            'page' => 'cancelled',
            'title' => 'Cancelled Requests',
            'subtitle' => 'All Cancelled Requests',
            'withdrawals' => $withdrawals,
        ]);
    }

    public function paymentHistory(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['Pending', 'Request', 'Requested', 'Approved', 'Rejected', 'Cancelled', 'Canceled'])],
            'memberid' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
        ]);

        $withdrawals = WithdrawalRequest::with('member')
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['memberid'] ?? null, fn (Builder $query, string $memberid) => $query->where('memberid', 'like', "%{$memberid}%"))
            ->when($filters['date'] ?? null, fn (Builder $query, string $date) => $query->whereDate('request_date', $date))
            ->latest('request_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.payment.index', [
            'page' => 'history',
            'title' => 'Payment History',
            'subtitle' => 'All Payment History',
            'withdrawals' => $withdrawals,
        ]);
    }
}
