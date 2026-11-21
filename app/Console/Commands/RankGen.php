<?php

namespace App\Console\Commands;

use App\Models\MemberDetail;
use App\Models\TotalMonthlyIncome;
use Illuminate\Console\Command;

class RankGen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rank-gen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ranks = [
            ['status' => 0, 'team' => 50, 'installment' => 3, 'amount' => 25, 'rank' => 'R1'],
            ['status' => 1, 'team' => 100, 'installment' => 3, 'amount' => 40, 'rank' => 'R2'],
            ['status' => 2, 'team' => 150, 'installment' => 3, 'amount' => 55, 'rank' => 'R3'],
            ['status' => 3, 'team' => 200, 'installment' => 3, 'amount' => 70, 'rank' => 'R4'],
            ['status' => 4, 'team' => 250, 'installment' => 3, 'amount' => 110, 'rank' => 'R5'],
            ['status' => 5, 'team' => 350, 'installment' => 4, 'amount' => 220, 'rank' => 'R6'],
            ['status' => 6, 'team' => 400, 'installment' => 4, 'amount' => 260, 'rank' => 'R7'],
            ['status' => 7, 'team' => 450, 'installment' => 4, 'amount' => 300, 'rank' => 'R8'],
            ['status' => 8, 'team' => 500, 'installment' => 4, 'amount' => 350, 'rank' => 'R9'],
            ['status' => 9, 'team' => 600, 'installment' => 4, 'amount' => 400, 'rank' => 'R10'],
            ['status' => 10, 'team' => 800, 'installment' => 5, 'amount' => 500, 'rank' => 'R11'],
            ['status' => 11, 'team' => 1000, 'installment' => 5, 'amount' => 600, 'rank' => 'R12'],
            ['status' => 12, 'team' => 1300, 'installment' => 5, 'amount' => 800, 'rank' => 'R13'],
            ['status' => 13, 'team' => 1500, 'installment' => 5, 'amount' => 900, 'rank' => 'R14'],
            ['status' => 14, 'team' => 1700, 'installment' => 5, 'amount' => 1050, 'rank' => 'R15'],
            ['status' => 15, 'team' => 2000, 'installment' => 5, 'amount' => 1500, 'rank' => 'R16'],
            ['status' => 16, 'team' => 2200, 'installment' => 5, 'amount' => 1600, 'rank' => 'R17'],
            ['status' => 17, 'team' => 2500, 'installment' => 5, 'amount' => 1700, 'rank' => 'R18'],
            ['status' => 18, 'team' => 2700, 'installment' => 5, 'amount' => 1800, 'rank' => 'R19'],
            ['status' => 19, 'team' => 3000, 'installment' => 5, 'amount' => 2000, 'rank' => 'R20'],
            ['status' => 20, 'team' => 3200, 'installment' => 6, 'amount' => 2300, 'rank' => 'R21'],
            ['status' => 21, 'team' => 3500, 'installment' => 6, 'amount' => 2500, 'rank' => 'R22'],
            ['status' => 22, 'team' => 3700, 'installment' => 6, 'amount' => 2700, 'rank' => 'R23'],
            ['status' => 23, 'team' => 4100, 'installment' => 6, 'amount' => 2800, 'rank' => 'R24'],
            ['status' => 24, 'team' => 4300, 'installment' => 6, 'amount' => 2900, 'rank' => 'R25'],
            ['status' => 25, 'team' => 4600, 'installment' => 6, 'amount' => 3000, 'rank' => 'R26'],
            ['status' => 26, 'team' => 4800, 'installment' => 6, 'amount' => 3200, 'rank' => 'R27'],
            ['status' => 27, 'team' => 5200, 'installment' => 6, 'amount' => 3500, 'rank' => 'R28'],
            ['status' => 28, 'team' => 5500, 'installment' => 6, 'amount' => 3850, 'rank' => 'R29'],
            ['status' => 29, 'team' => 6000, 'installment' => 1000, 'amount' => 4500, 'rank' => 'R30'],
        ];

        foreach ($ranks as $rankConfig) {
            $members = MemberDetail::where([
                ['status', 'Active'],
                ['rank_status', $rankConfig['status']]
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

                $insert = new TotalMonthlyIncome();
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
}
