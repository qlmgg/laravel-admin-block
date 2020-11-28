<?php

namespace App\Jobs\Block;

use App\Models\Block\Recording;
use App\Models\Block\RecordingPriceLog;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ContractCron implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $alljilu = Recording::with(["card", "user", "releaseuser"])->where([
            'winbidding' => 1,
            'userhave' => 1,
            'transfer_status' => 0
        ])->get();

        foreach ($alljilu as $jilu) {
            $this->OneContract($jilu);
        }
        Timing::RunsetInc("count", 1, [
            'jobname' => "ContractCron",
            'ymd' => Carbon::now()->format("Ymd"),
            'dec' => "合约收益升级",
            'count' => 1,
        ]);
    }

    private function OneContract($jilu)
    {
        $ymdnowprice = RecordingPriceLog::YmdIncPrice($jilu->id);
        // 异步执行 团队收益
        try {
            if ($ymdnowprice) {
                dispatch(new TeamEarningsQueue([
                    'uid' => $jilu->uid,
                    'credit' => "credit6",
                    'amount' => $ymdnowprice,
                    'configkey' => 'BLOCKCONTRACTUSERTREE',//合约分销配置
                ]));
            }

        } catch (\Exception $e) {
            return false;
        }
    }

}
