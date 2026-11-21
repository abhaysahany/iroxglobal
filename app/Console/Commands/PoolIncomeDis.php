<?php

namespace App\Console\Commands;

use App\Models\GlobalPoolIncome;
use App\Models\MemberDetail;
use App\Models\PoolUplineMember;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PoolIncomeDis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pool-income-dis';

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
                            'memberid'      => $memberid,
                            'pool_no'       => $value->pool_no,
                            'amount'        => $amount,
                            'total_income'  => $monthlyIncome,
                            'rate'          => $rate,
                            'name'          => getName($uplineId),
                            'activatingId'  => $uplineId,
                            'status'        => 'Paid',
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
}
