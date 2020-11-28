<?php

namespace App\Jobs\Block;

use App\Models\Block\Recording;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class GiveBackCreditCron implements ShouldQueue
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
       $yuyue = Recording::where([
           'batch_id'=>0,
           'type'=>1,
           'winbidding'=>0,
           'userhave'=>0,
           'refund'=>0,
       ])->get();
       foreach ($yuyue as $k=>$recording){
            $this->GiveBackCreditOne($recording);
       }

    }

    private function GiveBackCreditOne($recording){
        if($recording != null){
            $remark = '预约没有参与抢购退还积分';
            if($recording->deduct_credit  && $recording->deduct_amount){
                Recording::where('id',$recording->id)->update(['refund'=>1]);
                \App\Models\User::CurrencyChange($recording->uid,$recording->deduct_credit,$recording->deduct_amount,$remark);
            }
        }
    }
}
