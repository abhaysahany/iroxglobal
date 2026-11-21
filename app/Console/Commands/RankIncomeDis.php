<?php

namespace App\Console\Commands;

use App\Models\MemberDetail;
use App\Models\RankIncome;
use App\Models\TotalMonthlyIncome;
use Illuminate\Console\Command;

class RankIncomeDis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rank-income-dis';

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
        $incomes = TotalMonthlyIncome::where([['status', 'Active']])->whereDate('pay_date', date('Y-m-d'))->get();

        foreach ($incomes as $income) {
            $memberid = $income->memberid;
            $paid_installment = $income->paid_installment;


            if ($paid_installment < $income->installment) {
                // Income jayegi 3 bar mahine mein
                $insert = new RankIncome();
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
}
