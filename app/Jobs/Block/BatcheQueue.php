<?php

namespace App\Jobs\Block;


use App\Models\Block\Batche;
use App\Models\Block\CardSell;
use App\Models\Block\Recording;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BatcheQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cycle;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cycle)
    {
        //
        $this->cycle=$cycle;
        Log::info($cycle."正在执行.");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $cycle= $this->cycle;
        //事务开始
        DB::beginTransaction();
        try {
            $b = Batche::where("cycle",$cycle)->first();
            //参与者
            $partner= Recording::where("batch_id",$b->id)->orderBy("created_at")->get();
            if ($partner ==null){
                return;
            }
            $partners = $partner->toArray();

            $failure = [];
            $bonanza = [];
            $temp_release_uid = [];
            foreach ($partners as $k=>$v){
                if(in_array($v['release_uid'],$temp_release_uid)){
                    $failure[$k]=$v;
                }else{
                    $bonanza[$k]=$v;
                    $temp_release_uid[]=$v['release_uid'];
                }
            }
            Log::info($cycle."队列执行获胜数组：".json_encode($temp_release_uid));


            // 获胜者处理函数
            $this->winfun($bonanza);
            //失败者处理
            $this->failfun($failure);
            // 队列执行完毕.
            Batche::where("cycle",$cycle)->update([
                'status'=>1,
                'queue'=>1
            ]);
            //事务提交
            DB::commit();
        }catch (\Exception $e){
            //事务回滚
            Log::error("{$cycle}队列执行失败".$e->getMessage());
            DB::rollBack();
        }
    }


    private function winfun($wins){
        foreach ($wins as $k=>$win){
            //记录表 状态改为中标
            Recording::where("id",$win['id'])->update([
                'winbidding'=>1,
                'winbidding_at'=>Carbon::now()
            ]);
            $this->sendsms($win);
        }
    }

    private function sendsms(&$win){
        //发布人
        $release_uid = $win['release_uid'];
        $r = User::where("id",$release_uid)->first("phone");
        $rphone = $r->phone;
        Log::info("发送短信发布人".$rphone);

        //领取人
        $uid = $win['uid'];
        $u = User::where("id",$uid)->first("phone");
        $uphone = $u->phone;

        Log::info("发送短信领取人".$uphone);

    }



    //归还失败者的扣除
    private function failfun($fail){
        foreach ($fail as $v){
            if($v['uid'] && $v['deduct_credit'] && $v['deduct_amount']){
                Recording::where('id',$v['id'])->update(['refund'=>1]);
                $remark = "没有中标".$v['card_id']."归还".$v['deduct_amount'];
                \App\Models\User::CurrencyChange($v['uid'],$v['deduct_credit'],$v['deduct_amount'],$remark);
            }
        }
    }

}
