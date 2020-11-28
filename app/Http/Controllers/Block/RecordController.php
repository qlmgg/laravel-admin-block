<?php

namespace App\Http\Controllers\Block;

use App\Models\Block\CardSell;
use App\Models\Block\Recording;


use App\Models\Block\RecordingAppeal;
use App\Models\Block\RecordingPayReview;
use App\Models\Block\RecordingPriceLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class RecordController extends BaseController
{
    //
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adopt()
    {
        return view("record.adopt");
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pricehistroy()
    {
        $log = RecordingPriceLog::where("uid", $this->user->id())->get();
        $html = [
            'currencychinese' => "累计收益",
            'pricecount' => array_sum(array_column($log->toArray(), 'much'))
        ];
        return view("record.pricehistroy", ['html' => $html, 'log' => $log->toArray()]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transfer()
    {
        $user = $this->user->user();

        $waitlist = Recording::with(["card"])->where([
            'uid' => $user->id,
            'winbidding' => 1,
            'userhave' => 1,
            'transfer_status' => 1
        ])->get()->toArray();
        foreach ($waitlist as $k => $item) {
            $waitlist[$k]['cardpic'] =$this->blockService->FullImage($item['card']['pic']);
            $waitlist[$k]['cardname'] = $item['card']['name'];
            $waitlist[$k]['jiazhi'] = "{$item['price']}({$item['card']['min_worth_price']}-{{$item['card']['max_worth_price']}})";
            $waitlist[$k]['heyue'] = $item['card']['profit_day'] . "天/" . $item['card']['profit_rate'] . "%";
            $waitlist[$k]['shouyi'] = $item['price'];
        }
        //别人已经中标的但是没有拥有
        $inlist = Recording::with(["card", 'user'])->where([
            'winbidding' => 1,
            'release_uid' => $user->id,
            'transfer_status' => 0,
            'userhave' => 0
        ])->get()->toArray();
        foreach ($inlist as $k => $item) {
            $inlist[$k]['cardpic'] = $this->blockService->FullImage($item['card']['pic']);
            $inlist[$k]['cardname'] = $item['card']['name'];
            $inlist[$k]['heyue'] = $item['card']['profit_day'] . "天/" . $item['card']['profit_rate'] . "%";
            if ($item['user']) {
                $inlist[$k]['zhuanrangfang'] = $item['user']['phone'];
            }
        }


        $r = RecordingPayReview::where([
            'status' => 1,
            'from_uid' => $user['id'],
        ])->get("recording_id");
        $recording_ids = array_column($r->toArray(), 'recording_id');

        $overlist = Recording::with(["card", 'user'])->whereIn("id", $recording_ids)->get()->toArray();
        foreach ($overlist as $k => $item) {
            $overlist[$k]['cardpic'] =$this->blockService->FullImage($item['card']['pic']);
            $overlist[$k]['cardname'] = $item['card']['name'];
            $overlist[$k]['jiazhi'] = "{$item['price']}({$item['card']['min_worth_price']}-{{$item['card']['max_worth_price']}})";
            $overlist[$k]['heyue'] = $item['card']['profit_day'] . "天/" . $item['card']['profit_rate'] . "%";
            $overlist[$k]['shouyi'] = $item['price'];
        }
        //申诉列表
        $shensulist = RecordingAppeal::with("recording")->where('complaint_uid', $user->id)->get();
        if ($shensulist != null) {
            foreach ($shensulist as $k => $item) {
                $shensulist[$k]['recordinghashblock'] = $item['recording']['hashblock'];
            }
        }
        return view("record.transfer", [
            'waitlist' => $waitlist,
            'inlist' => $inlist,
            'overlist' => $overlist,
            'shensulist' => $shensulist,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function confirmadoption(Request $request)
    {
        $id = $request->input("id");
        $user = $this->user->user();
        $recording = Recording::where([
            'id' => $id,
            'uid' => $user->id,
            'winbidding' => 1,
            'userhave' => 0,
        ])->first();
        if ($recording == null) {
            return $this->error("找不到记录");
        }
        if ($recording->release_uid) {
            return $this->success("跳转支付", route("block.payr.recording", ['id' => $recording->id]));
        }
        //没有发布人id就直接拥有
        Recording::where([
            'id' => $id,
            'uid' => $user->id,
            'winbidding' => 1,
            'userhave' => 0,
        ])->update([
            'userhave' => 1,
            'userhave_at' => Carbon::now(),
        ]);
        return $this->success("没有付款人id 自动拥有");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function sellRecording(Request $request)
    {
        $user = $this->user->user();
        $id = $request->input('id');

        $where = [
            'id' => $id,
            'uid' => $user->id,
            'winbidding' => 1,
            'userhave' => 1,
            'transfer_status' => 0
        ];
        $recording = Recording::with(["card"])->where($where)->first();

        if ($recording == null) {
            return $this->error("找不到该记录");
        }
        if ($this->adoptedButtion($recording)) {
            return $this->error("24小时之内无法提前销售");
        }
        $sell = CardSell::where([
            'recording_id' => $recording->id,
            'uid' => $user->id,
            'card_id' => $recording->card_id,
            'is_sale' => 1,
        ])->first();

        if ($sell != null) {
            return $this->success("已提交过了");
        }
        // 会员提现出售 需审核
        CardSell::NoCardSellSale([
            'uid' => $user->id,
            'card_id' => $recording->card_id,
            'recording_id' => $recording->id,
            'price' => $recording->price,
        ]);

        Recording::where('id',$recording->id)->update([
            'transfer_status' => 1
        ]);
        //收益转存
        User::CurrencyChange($user->id, "credit2", $recording->price, "合约收益转存");
        return $this->success("发布成功");

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function adoptlist(Request $request)
    {
        $user = $this->user->user();
        $where = [
            'uid' => $user->id,
            'winbidding' => 1,
            'transfer_status' => 0
        ];
        $recording = Recording::with(["card"])->where($where)->get()->toArray();

        foreach ($recording as $k => $item) {
            if ($item['card']) {
                $recording[$k]['blockname'] = $item['card']['name'];
                $DTC = $item['price'] / 2;
                $recording[$k]['blockjiazhi'] = "当前价值{$item['price']}≈{$DTC} DTC";;
                $recording[$k]['blockheyue'] = $item['card']['profit_rate'] . "% * " . $item['card']['profit_day'] . "天";
                //当天的收益
                $recording[$k]['nowshouyi'] = RecordingPriceLog::ymdPrice($item);
                //现在出售的价格
                $recording[$k]['chushoujiage'] = $item['price'];
                //提交出售可以进入余额的钱
                $recording[$k]['zhuancun'] = $item['price'];
            }
        }
        //领养中
        $adoptinglist = [];
        //已领养
        $adoptedlist = [];

        Arr::where($recording, function ($value, $key) use (&$adoptedlist, &$adoptinglist) {
            if ($value['userhave'] == 0) {
                //倒计时 s
                $created = Carbon::parse($value['created_at'])->addHour(2);
                $value['daojishi'] = $created->diffInSeconds(Carbon::now());

                $value["buttion"] = $this->adoptingButtion($value);
                $adoptinglist[$key] = $value;
            }
            if ($value['userhave'] == 1) {
                $value["buttion"] = $this->adoptedButtion($value);
                $adoptedlist[$key] = $value;
            }
        });

        //申诉列表
        $shensulist = RecordingAppeal::with("recording")->where('accused_uid', $user->id)->get();
        if ($shensulist != null) {
            foreach ($shensulist as $k => $item) {
                $shensulist[$k]['recordinghashblock'] = $item['recording']['hashblock'];
            }
        }


        return $this->success("获取数据成功", '', [
            'adoptinglist' => $adoptinglist,
            'adoptedlist' => $adoptedlist,
            'shensulist' => $shensulist
        ]);
    }

    /**
     * @param $recording
     * @return int
     */
    function adoptingButtion($recording)
    {
        $p = RecordingPayReview::where("recording_id", $recording['id'])->first();
        if ($p != null) {
            return "待确定";
        }
        return  "待支付";
    }

    /**
     * @param $recording
     * @return int
     */
    function adoptedButtion($recording)
    {
        if ($recording['userhave_at']) {
            // 一天之内领养的 不能发起提前出售
            $userhave_at1 = Carbon::parse($recording['userhave_at'])->addDay(1)->timestamp;
            if (Carbon::now()->timestamp <= $userhave_at1) {
                return 1;
            }
        }
        // 提前发布出售
        return 0;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function yuyue()
    {

        $user = $this->user->user();
        $recording = Recording::with(["card"])->where([
            'uid' => $user->id,
            'type' => 1
        ])->get()->toArray();
        foreach ($recording as $k => $item) {
            $recording[$k]['cardname'] = $item['card']['name'];
            if ($item['winbidding'] == 1) {
                $recording[$k]['statusinfo'] = "抢到";
            } else {
                $recording[$k]['statusinfo'] = "未抢到";
            }
        }
        return view("record.yuyue", ['recording' => $recording]);
    }
}
