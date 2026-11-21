<?php

namespace App\Console\Commands;

use App\Models\MemberDetail;
use App\Models\Setting;
use App\Models\WeeklyCount;
use Illuminate\Console\Command;

class UpdateWeeklyCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-weekly-count';

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
        if (date('l') == 'Monday') {

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
                $mem->weekly_count += 1;
                $mem->save();

                $weekly = new WeeklyCount;
                $weekly->memberid = $sponsorid;
                $weekly->prev_count = $prev_count;
                $weekly->total_count = $prev_count + 1;
                $weekly->type = 'Self';
                $weekly->downid = $value->memberid;
                $weekly->save();
            }
        }
    }
}
