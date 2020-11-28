<?php

namespace App\Jobs\Block;

use App\Models\Config;
use App\Models\Timing;
use App\Models\User;
use App\Models\UserTree;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TeamEarningsQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $parameter;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($parameter)
    {

        if (!array_key_exists('uid', $parameter)) {
            throw  new  \Exception("No Find KEY 【uid】");
        }
        if (!array_key_exists('amount', $parameter)) {
            throw  new  \Exception("No Find KEY 【amount】");
        }
        if (!array_key_exists('credit', $parameter)) {
            throw  new  \Exception("No Find KEY 【credit】");
        }
        if (!array_key_exists('configkey', $parameter)) {
            throw  new  \Exception("No Find KEY 【configkey】");
        }
        $this->parameter = $parameter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parameter = $this->parameter;
        if ($parameter['amount'] == 0) {
            return null;
        }
        $fatherlist = UserTree::UserFathertree($parameter['uid']);
        $res = [];
        if ($fatherlist) {
            foreach ($fatherlist as $f) {
                $res[] = $this->userOneRevenue($f);
            }
        }
        if ($res) {
            Timing::RunsetInc("count", 1, [
                'jobname' => "TeamEarningsQueue",
                'ymd' => Carbon::now()->format("Ymd"),
                'dec' => "团队收益",
                'count' => 1,
            ]);
        }
    }

    //单个人的收益
    private function userOneRevenue($user)
    {
        $amount = $this->parameter['amount'];
        $currency = $this->parameter['credit'];
        $fenxiao = Config::GetKeyValue($this->parameter['configkey'],"value");

        if (empty($fenxiao) || !$amount || !$currency) {
            return null;
        }
        if (count($fenxiao) > 0) {
            $much = $amount * ($fenxiao[$user['root'] - 1]['scale'] / 100);
            User::CurrencyChange($user['id'], $currency, $much, "第".$user['root']."级返佣收益");
        }
        return true;
    }

}
