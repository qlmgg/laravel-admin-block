<?php

namespace App\Jobs\Block;

use App\Models\Block\Batche;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BatcheCron implements ShouldQueue
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

        $br = Batche::where([
            "queue"=>0,
            'status'=>0
        ])->get();
        if ($br == null){
            Log::info("没有队列可以自行");
            return;
        }
        $b = $br->toArray();
        if($b){
            foreach ($b as $v){
                dispatch(new BatcheQueue($v['cycle']));
            }
        }
        Timing::RunsetInc("count",1,[
            'jobname'=>"BatcheCron",
            'ymd'=>Carbon::now()->format("Ymd"),
            'dec'=>"抢购队列每分钟执行一次",
            'count'=>1,
        ]);
    }
}
