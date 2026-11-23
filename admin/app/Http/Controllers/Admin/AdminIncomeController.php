<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DirectIncome;
use App\Models\GlobalPoolIncome;
use App\Models\Income;
use App\Models\LevelIncome;
use App\Models\Member;
use App\Models\MemberDetail;
use App\Models\RankIncome;
use App\Models\StackingIncome;
use App\Models\StakingDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminIncomeController extends Controller
{
    public function index(Request $request): View
    {
        $members = Member::latest()->get();
        $incomes = Income::with('member')
            ->when($request->member_id, fn($query, $memberId) => $query->where('member_id', $memberId))
            ->when($request->income_type, fn($query, $type) => $query->where('income_type', $type))
            ->when($request->status, fn($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();
        $totalIncome = Income::sum('amount');
        $incomeTypes = ['Direct Income', 'Level Income', 'ROI Income', 'Bonus Income', 'Referral Income'];

        return view('admin.incomes.index', compact('members', 'incomes', 'totalIncome', 'incomeTypes'));
    }

    public function community(): View
    {
        $title = 'Community Income';
        $type = 'community';
        $incomes = $this->incomeRows(['Community Income', 'Level Income']);

        return view('admin.incomes.table', compact('title', 'incomes', 'type'));
    }

    public function direct(): View
    {
        return $this->directIncome();
    }

     public function rankIncome(): View
    {
        $incomes = RankIncome::with('member')->latest()->get();

        return view('admin.incomes.rank-income', compact('incomes'));
    }

    public function rankIncomeManualTransfer(): View
    {
        return view('admin.incomes.rank-income-manual-transfer');
    }

    public function storeRankIncomeManualTransfer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'memberid' => ['required', 'string', 'exists:member_details,memberid'],
            'rank' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'name' => ['nullable', 'string', 'max:100'],
            'installment' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:Pending,Paid'],
        ]);

        DB::transaction(function () use ($validated): void {
            $member = MemberDetail::where('memberid', $validated['memberid'])
                ->lockForUpdate()
                ->firstOrFail();

            $income = new RankIncome();
            $income->memberid = $validated['memberid'];
            $income->rank = $validated['rank'];
            $income->amount = $validated['amount'];
            $income->name = $validated['name'] ?: $member->name;
            $income->installment = $validated['installment'];
            $income->status = $validated['status'];
            $income->save();

            if ($income->status === 'Paid') {
                $previousWallet = (float) $member->wallet;
                $member->wallet = $previousWallet + (float) $income->amount;
                $member->save();

                walletTransfer(
                    $member->memberid,
                    $income->amount,
                    'debit',
                    $previousWallet,
                    'Rank Income',
                    'Manual Rank Income amount added into wallet.'
                );
            }
        });

        return redirect()
            ->route('admin.incomes.rank.manual-transfer')
            ->with('success', 'Rank Income transferred successfully.');
    }

    public function stakingIncome(): View
    {
        $incomes = StackingIncome::with('member')->latest()->get();

        return view('admin.incomes.staking-income', compact('incomes'));
    }

    public function directIncome(): View
    {
        $incomes = DirectIncome::with('member')->latest()->get();

        return view('admin.incomes.direct-income', compact('incomes'));
    }

    public function levelIncome(): View
    {
        $incomes = LevelIncome::with('member')->latest()->get();

        return view('admin.incomes.level-income', compact('incomes'));
    }

      public function globalRec(): View
    {
        $incomes = GlobalPoolIncome::where('pool_no', 1)->orderby('created_at', 'desc')->get();

        return view('admin.incomes.global-rec', compact('incomes'));
    }

    public function globalRecStar(): View
    {
        $incomes = GlobalPoolIncome::where('pool_no', 2)->orderby('created_at', 'desc')->get();

        return view('admin.incomes.global-rec-star', compact('incomes'));
    }

    public function sponsorRoi(): View
    {
        $title = 'Sponsor ROI Income';
        $type = 'sponsor-roi';
        $incomes = $this->incomeRows(['Sponsor ROI Income', 'ROI Income']);

        return view('admin.incomes.table', compact('title', 'incomes', 'type'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'income_type' => ['required', 'in:Direct Income,Level Income,ROI Income,Bonus Income,Referral Income'],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:Pending,Paid'],
            'payout_date' => ['nullable', 'date'],
        ]);

        Income::create($data);

        return back()->with('success', 'Created successfully.');
    }

    public function markPaid(Income $income): RedirectResponse
    {
        $income->update(['status' => 'Paid', 'payout_date' => now()->toDateString()]);

        return back()->with('success', 'Updated successfully.');
    }

    private function incomeRows(array $incomeTypes): mixed
    {
        return Income::with('member')
            ->when(
                Schema::hasColumn('incomes', 'income_type'),
                fn($query) => $query->whereIn('income_type', $incomeTypes)
            )
            ->latest()
            ->paginate(10);
    }

    public function roiIncomeDistribution(): View
    {
        return view('admin.incomes.roi-income-dis');
    }

    public function roiIncomeDistribute(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric'],
        ]);

        $amount = $request->input('amount');

        $check = StackingIncome::whereDate('created_at', now()->toDateString())->exists();

        if ($check) {
            return back()->with('error', 'ROI distribute for today.');
        }
        
        if ($amount <= 0) {
            return back()->with('error', 'Amount must be greater than 0.');
        }

        $staking = StakingDetail::where('status', 'Active')->get();

        foreach ($staking as  $stake) {
            $capping = $stake->invest_amount * $stake->capping;
            $balance = $capping - $stake->paid_amount;

            if ($amount >= $balance) {

                $amount = $balance;
            }

            if ($amount > 0) {
                $income = new StackingIncome();
                $income->memberid = $stake->memberid;
                $income->amount = $amount;
                $income->total_investment = $stake->invest_amount;
                $income->date = date('Y-m-d');
                $income->status = 'Paid';
                $income->save();

                $stake->paid_amount += $amount;
                if ($stake->paid_amount >= $capping) {
                    $stake->status = 'Deactive';
                }
                $stake->save();

                $memUpdate = MemberDetail::where('memberid', $stake->memberid)->first();

                $wallet = $memUpdate->wallet;
                $memUpdate->wallet += $amount;
                $memUpdate->save();

                walletTransfer($stake->memberid, $amount, 'debit', $wallet, 'ROI Income', 'ROI Income amount has been transfered into Wallet.');
            } else {
                $stake->status = 'Deactive';
                $stake->save();
            }
            
        }
        
        session()->flash('success', 'ROI Income Distribution completed successfully.');
        return redirect()->back();
    }
}
