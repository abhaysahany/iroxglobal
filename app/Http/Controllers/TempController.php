<?php

namespace App\Http\Controllers;

use App\Models\GlobalPoolIncome;
use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\PoolUplineMember;
use App\Models\RankIncome;
use App\Models\Setting;
use App\Models\StackingIncome;
use App\Models\StakingDetail;
use App\Models\TotalMonthlyIncome;
use App\Models\WeeklyCount;
use Illuminate\Support\Facades\DB;

class TempController extends Controller
{
    public function rankGen()
    {
        $ranks = [
            // ['status' => 0, 'team' => 50, 'installment' => 3, 'amount' => 25, 'rank' => 'R1'],
            // ['status' => 1, 'team' => 100, 'installment' => 3, 'amount' => 40, 'rank' => 'R2'],
            // ['status' => 2, 'team' => 150, 'installment' => 3, 'amount' => 55, 'rank' => 'R3'],
            // ['status' => 3, 'team' => 200, 'installment' => 3, 'amount' => 70, 'rank' => 'R4'],
            // ['status' => 4, 'team' => 250, 'installment' => 3, 'amount' => 110, 'rank' => 'R5'],
            // ['status' => 5, 'team' => 350, 'installment' => 4, 'amount' => 220, 'rank' => 'R6'],
            // ['status' => 6, 'team' => 400, 'installment' => 4, 'amount' => 260, 'rank' => 'R7'],
            // ['status' => 7, 'team' => 450, 'installment' => 4, 'amount' => 300, 'rank' => 'R8'],
            // ['status' => 8, 'team' => 500, 'installment' => 4, 'amount' => 350, 'rank' => 'R9'],
            // ['status' => 9, 'team' => 600, 'installment' => 4, 'amount' => 400, 'rank' => 'R10'],
            // ['status' => 10, 'team' => 800, 'installment' => 5, 'amount' => 500, 'rank' => 'R11'],
            // ['status' => 11, 'team' => 1000, 'installment' => 5, 'amount' => 600, 'rank' => 'R12'],
            // ['status' => 12, 'team' => 1300, 'installment' => 5, 'amount' => 800, 'rank' => 'R13'],
            // ['status' => 13, 'team' => 1500, 'installment' => 5, 'amount' => 900, 'rank' => 'R14'],
            // ['status' => 14, 'team' => 1700, 'installment' => 5, 'amount' => 1050, 'rank' => 'R15'],
            // ['status' => 15, 'team' => 2000, 'installment' => 5, 'amount' => 1500, 'rank' => 'R16'],
            // ['status' => 16, 'team' => 2200, 'installment' => 5, 'amount' => 1600, 'rank' => 'R17'],
            // ['status' => 17, 'team' => 2500, 'installment' => 5, 'amount' => 1700, 'rank' => 'R18'],
            // ['status' => 18, 'team' => 2700, 'installment' => 5, 'amount' => 1800, 'rank' => 'R19'],
            // ['status' => 19, 'team' => 3000, 'installment' => 5, 'amount' => 2000, 'rank' => 'R20'],
            // ['status' => 20, 'team' => 3200, 'installment' => 6, 'amount' => 2300, 'rank' => 'R21'],
            // ['status' => 21, 'team' => 3500, 'installment' => 6, 'amount' => 2500, 'rank' => 'R22'],
            // ['status' => 22, 'team' => 3700, 'installment' => 6, 'amount' => 2700, 'rank' => 'R23'],
            // ['status' => 23, 'team' => 4100, 'installment' => 6, 'amount' => 2800, 'rank' => 'R24'],
            // ['status' => 24, 'team' => 4300, 'installment' => 6, 'amount' => 2900, 'rank' => 'R25'],
            // ['status' => 25, 'team' => 4600, 'installment' => 6, 'amount' => 3000, 'rank' => 'R26'],
            // ['status' => 26, 'team' => 4800, 'installment' => 6, 'amount' => 3200, 'rank' => 'R27'],
            // ['status' => 27, 'team' => 5200, 'installment' => 6, 'amount' => 3500, 'rank' => 'R28'],
            // ['status' => 28, 'team' => 5500, 'installment' => 6, 'amount' => 3850, 'rank' => 'R29'],
            // ['status' => 29, 'team' => 6000, 'installment' => 1000, 'amount' => 4500, 'rank' => 'R30'],
            ['status' => 0, 'team' => 50, 'installment' => 3, 'amount' => 25, 'rank' => 'R1'],
            ['status' => 1, 'team' => 100, 'installment' => 3, 'amount' => 40, 'rank' => 'R2'],
            ['status' => 2, 'team' => 150, 'installment' => 3, 'amount' => 55, 'rank' => 'R3'],
            ['status' => 3, 'team' => 400, 'installment' => 3, 'amount' => 260, 'rank' => 'R4'],
            ['status' => 4, 'team' => 600, 'installment' => 3, 'amount' => 400, 'rank' => 'R5'],

            ['status' => 5, 'team' => 800, 'installment' => 4, 'amount' => 500, 'rank' => 'R6'],
            ['status' => 6, 'team' => 1000, 'installment' => 4, 'amount' => 600, 'rank' => 'R7'],
            ['status' => 7, 'team' => 1500, 'installment' => 4, 'amount' => 900, 'rank' => 'R8'],
            ['status' => 8, 'team' => 2000, 'installment' => 4, 'amount' => 1500, 'rank' => 'R9'],
            ['status' => 9, 'team' => 2500, 'installment' => 4, 'amount' => 1700, 'rank' => 'R10'],

            ['status' => 10, 'team' => 3000, 'installment' => 6, 'amount' => 2000, 'rank' => 'R11'],
            ['status' => 11, 'team' => 4100, 'installment' => 6, 'amount' => 2800, 'rank' => 'R12'],
            ['status' => 12, 'team' => 4800, 'installment' => 6, 'amount' => 3200, 'rank' => 'R13'],
            ['status' => 13, 'team' => 5500, 'installment' => 6, 'amount' => 5500, 'rank' => 'R14'],
            ['status' => 14, 'team' => 6000, 'installment' => 1000, 'amount' => 6000, 'rank' => 'R15'],
        ];

        foreach ($ranks as $rankConfig) {
            $members = MemberDetail::where([
                ['status', 'Active'],
                ['rank_status', $rankConfig['status']],
            ])->get();

            foreach ($members as $value) {

                $weekly_count = $value->weekly_count;
                $member_team = $value->team;
                $total = $weekly_count + $member_team;

                if ($total < $rankConfig['team']) {
                    continue;
                }

                $value->rank_status += 1;
                $value->rank = $rankConfig['rank'];
                $value->save();

                $insert = new TotalMonthlyIncome;
                $insert->memberid = $value->memberid;
                $insert->installment = $rankConfig['installment'];
                $insert->paid_installment = 0;
                $insert->amount = $rankConfig['amount'];
                $insert->rank = $rankConfig['rank'];
                $insert->pay_date = date('Y-m-d H:i:s', strtotime('30 days'));
                $insert->status = 'Active';
                $insert->save();
            }
        }
    }

    public function rankIncomeDis()
    {
        $incomes = TotalMonthlyIncome::where([['status', 'Active']])->whereDate('pay_date', date('Y-m-d'))->get();

        foreach ($incomes as $income) {
            $memberid = $income->memberid;
            $paid_installment = $income->paid_installment;

            if ($paid_installment < $income->installment) {
                // Income jayegi 3 bar mahine mein
                $insert = new RankIncome;
                $insert->memberid = $memberid;
                $insert->rank = $income->rank;
                $insert->amount = $income->amount;
                $insert->installment = $paid_installment + 1;
                $insert->status = 'Paid';
                $insert->save();

                $mem = MemberDetail::where('memberid', $memberid)->first();
                $wallet = $mem->wallet;
                $mem->wallet += $income->amount;
                $mem->save();
                walletTransfer($memberid, $income->amount, 'debit', $wallet, ' Rank Income', 'Rank Income Amount Added into wallet.');

                $income->paid_installment += 1;
                $income->pay_date = date('Y-m-d H:i:s', strtotime('+30 days'));
                if ($income->paid_installment >= $income->installment) {
                    $income->status = 'Deactive';
                }
                $income->save();
            }
        }
    }

    public function renewDate()
    {
        $stakings = StakingDetail::where('renew_date', date('Y-m-d'))->get();
        foreach ($stakings as $staking) {
            $staking->renew_date = date('Y-m-d', strtotime('+365 days'));
            if ($staking->capping_year < 3) {
                $staking->capping += 1;
                $staking->capping_year += 1;
            } else {
                $staking->capping = 3;
                $staking->capping_year = 1;
            }
            $staking->paid_amount = 0;
            $staking->status = 'Active';
            $staking->save();
        }
    }

    public function poolIncomeDis()
    {
        PoolUplineMember::query()->chunkById(500, function ($poolMembers) {

            foreach ($poolMembers as $value) {

                $memberid = $value->memberid;
                $rate = $value->pool_no == 1 ? 10 : 15;
                $uplines = [
                    $value->upline_1,
                    $value->upline_2,
                ];

                foreach ($uplines as $uplineId) {

                    if (empty($uplineId)) {
                        continue;
                    }

                    $monthlyIncome = getmonthlyincome($uplineId);
                    $amount = ($monthlyIncome * $rate) / 100;

                    if ($amount <= 0) {
                        continue;
                    }

                    DB::transaction(function () use ($memberid, $value, $uplineId, $monthlyIncome, $amount, $rate) {
                        GlobalPoolIncome::create([
                            'memberid' => $memberid,
                            'pool_no' => $value->pool_no,
                            'amount' => $amount,
                            'total_income' => $monthlyIncome,
                            'rate' => $rate,
                            'name' => getName($uplineId),
                            'activatingId' => $uplineId,
                            'status' => 'Paid',
                        ]);

                        $mem = MemberDetail::where('memberid', $memberid)->first();
                        $oldWallet = $mem->wallet;
                        $mem->increment('wallet', $amount);

                        walletTransfer(
                            $memberid,
                            $amount,
                            'debit',
                            $oldWallet,
                            'Pool Income',
                            'Pool Income Amount Added into wallet.'
                        );
                    });
                }
            }
        });
    }

    public function updateWeeklyCount()
    {
        // if (date('l') == 'Monday') {

        $var = MemberDetail::where([['status', 'Active'], ['downline', '>=', 2]])->get();
        foreach ($var as $value) {
            $sponsorid = $value->sponsorid;
            $prev_count = $value->weekly_count;

            $value->weekly_count += 2;
            $value->save();

            $weekly = new WeeklyCount;
            $weekly->memberid = $value->memberid;
            $weekly->prev_count = $prev_count;
            $weekly->total_count = $prev_count + 2;
            $weekly->type = 'Self';
            $weekly->save();

            $set = Setting::find(1);
            $check = $set->set_weekly_count;

            if ($sponsorid == null || $sponsorid == 'Root') {
                continue;
            }

            $mem = MemberDetail::where([['memberid', $sponsorid], ['status', 'Active'], ['downline', '>=', 2]])->first();

            if (! $mem) {
                continue;
            }

            $prev_count = $mem->weekly_count;
            $mem->weekly_count += $check;
            $mem->save();

            $weekly = new WeeklyCount;
            $weekly->memberid = $sponsorid;
            $weekly->prev_count = $prev_count;
            $weekly->total_count = $prev_count + 2;
            $weekly->type = 'Self';
            $weekly->downid = $value->memberid;
            $weekly->save();
        }
        // }
    }

    /**
     * Generate unique random wallet addresses for existing members who do not have one.
     */
    // public function generateMemberWallets()
    // {
    //     $members = MemberDetail::all();
    //     $updatedCount = 0;

    //     foreach ($members as $member) {
    //         // Check if member's wallet address is NULL, empty, or whitespace
    //         if (empty(trim((string) $member->random_address))) {
    //             do {
    //                 $random_address = generateRandomAddress();
    //             } while (MemberDetail::where('random_address', $random_address)->exists());

    //             $member->random_address = $random_address;
    //             $member->save();
    //             $updatedCount++;
    //         }
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => "Generated wallet addresses for {$updatedCount} members successfully.",
    //         'updated_count' => $updatedCount,
    //     ]);
    // }

    public function levelIncomeDis()
    {
        $rates = [
            1 => 30,
            2 => 25,
            3 => 20,
            4 => 15,
            5 => 10,
            6 => 9,
            7 => 8,
            8 => 7,
            9 => 6,
            10 => 5,
            11 => 4,
            12 => 3,
            13 => 2,
            14 => 1,
            15 => 0.90,
            16 => 0.80,
            17 => 0.70,
            18 => 0.60,
            19 => 0.50,
            20 => 0.40,
            21 => 0.30,
            22 => 0.20,
            23 => 0.10,
            24 => 0.09,
            25 => 0.08,
            26 => 0.07,
            27 => 0.06,
            28 => 0.05,
            29 => 0.04,
            30 => 0.03,
        ];

        $incomes = StackingIncome::where([['status', 'Paid'], ['level_pay', 0]])
            ->limit(50)
            ->get();

        foreach ($incomes as $value) {
            $memberid = $value->memberid;
            $roiAmount = $value->amount;

            $memData = MemberDetail::where('memberid', $memberid)->first();
            if (! $memData) {
                $value->level_pay = 1;
                $value->save();

                continue;
            }

            $sponsorid = $memData->sponsorid;

            for ($i = 1; $i <= 30; $i++) {
                if ($sponsorid == null || $sponsorid == 'Root') {
                    break;
                }

                $mem = MemberDetail::where([['memberid', $sponsorid], ['status', 'Active']])->first();

                if ($mem) {
                    $rate = $rates[$i];
                    $amount = ($roiAmount * $rate) / 100;

                    if ($amount > 0 && $mem->level_downline >= 2) {
                        $insert = new LevelIncome;
                        $insert->memberid = $sponsorid;
                        $insert->level = $i;
                        $insert->amount = $amount;
                        $insert->level_id = $memberid;
                        $insert->name = $memData->name;
                        $insert->staking_income = $roiAmount;
                        $insert->status = 'Paid';
                        $insert->save();

                        $wallet = $mem->wallet;
                        $mem->wallet += $amount;
                        $mem->save();

                        walletTransfer($sponsorid, $amount, 'debit', $wallet, 'Level Income', $i.' Level Income Amount Added into wallet.');
                    }

                    $sponsorid = $mem->sponsorid;
                } else {
                    $next = MemberDetail::where('memberid', $sponsorid)->first();
                    $sponsorid = $next ? $next->sponsorid : null;
                }
            }

            $value->level_pay = 1;
            $value->save();
        }
    }
}
