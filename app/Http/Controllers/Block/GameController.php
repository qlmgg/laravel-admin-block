<?php

namespace App\Http\Controllers\Block;


use App\Models\Block\LotteryLog;
use App\Models\User;
use App\Models\UserSign;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GameController extends BaseController
{
    /**
     * @var array
     */
    private $prize = [
        ['id' => 1, 'name' => "iPhone 11 Pro Max", 'images' => "/block/images/choujiang/case/apple.jpg",],
        ['id' => 2, 'name' => "60积分", 'images' => "/block/images/choujiang/case/money.jpg",],
        ['id' => 3, 'name' => "5888现金红包", 'images' => "/block/images/choujiang/case/hongbao.png",],
        ['id' => 4, 'name' => "100积分", 'images' => "/block/images/choujiang/case/apple.jpg",],
        ['id' => 5, 'name' => "888现金红包", 'images' => "/block/images/choujiang/case/hongbao.png",],
        ['id' => 6, 'name' => "60积分", 'images' => "/block/images/choujiang/case/money.jpg",],
        ['id' => 7, 'name' => "3888现金红包", 'images' => "/block/images/choujiang/case/hongbao.png",],
        ['id' => 8, 'name' => "谢谢参与", 'images' => "/block/images/choujiang/case/think.png",]
    ];
    /**
     * @var int
     */
    private $needmoney = 388;

    //

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lottery()
    {

        //付出积分
        $paycurrency = "credit2";
        $user = $this->user->user();

        $list = $this->prize;
        $ids = array_column($list, "id");
        $data = [
            'uid' => $user->id,
            'needdoge' => $this->needmoney,
            'havedoge' => CreditNumberFormat($user->$paycurrency),
            'cha' => $user->$paycurrency . "-" . $this->needmoney,
            'ids' => implode(",", $ids)
        ];
        $map = array(
            'uid' => $user->id,
        );
        $log = LotteryLog::where($map)->get()->toArray();

        return view("game.lottery", [
            'list' => $list,
            'data' => $data,
            'reslist' => $log
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lotterypost(Request $request)
    {
        $paycurrency = "credit2";
        $identity = $this->identity();
        $lottery_id = 8;
        if (!$identity) {
            return $this->error("还没实名认证审核");
        }
        $user = $this->user->user();
        if ($user->$paycurrency < $this->needmoney) {
            return $this->error("成就点余额不足，无法抽奖!");
        }
        $info = $this->prize[$lottery_id - 1];
        $remark = "用户" . $user->name . "参与抽奖，花费" . $this->needmoney . $paycurrency;
        $usermoney = User::CurrencyChange($user->id, $paycurrency, -($this->needmoney), $remark);
        $log = LotteryLog::insert([
            'uid' => $user->id,
            'phone' => $user->phone,
            'prizename' => $info['name'],
            'deduct_credit' => $paycurrency,
            'deduct_amount' => $this->needmoney,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        if (!$log || !$usermoney) {
            return $this->error("参与失败，重新参与");
        }
        return $this->success("抽奖成功", "", $info);
    }

    public function sign(Request $request){
        $user = $this->user->user();
        $count = UserSign::where([
            'uid'=>$user->id,
            'ymd'=>Carbon::now()->format("Ymd")
        ])->count();
        if($count > 0){
            return $this->error("您已经签到过了");
        }
        UserSign::create([
            'uid'=>$user->id,
            'source'=>0,//自己签到
            'status'=>0,
        ]);
        return $this->success("签到成功");
    }

}
