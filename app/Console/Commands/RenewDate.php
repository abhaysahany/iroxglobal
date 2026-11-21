<?php

namespace App\Console\Commands;

use App\Models\StakingDetail;
use Illuminate\Console\Command;

class RenewDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:renew-date';

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
}
