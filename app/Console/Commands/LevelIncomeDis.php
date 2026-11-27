<?php

namespace App\Console\Commands;

use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\StackingIncome;
use Illuminate\Console\Command;

class LevelIncomeDis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:level-income-dis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute Level Income based on ROI (Batch of 50)';

    /**
     * Execute the console command.
     */
    public function handle()
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
                $levelDownline = $mem->level_downline;

                if ($mem) {
                    $rate = $rates[$i];
                    $amount = ($roiAmount * $rate) / 100;

                    if ($amount > 0 && $levelDownline >= 2) {
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

        $this->info('Batch of 50 Level Income processed successfully.');
    }
}
