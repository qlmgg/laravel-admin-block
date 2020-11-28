<?php

namespace App\Http\Controllers\Block;

use App\Models\Block\CardSell;
use App\Models\Block\Recording;
use App\Models\CreditLog;
use App\Models\CreditReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;

class CreditController extends BaseController
{

    //
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history(Request $request)
    {
        $credit = $request->get("credit", "credit1");
        $user = $this->user->user();

        $buttion = '';
        $help = '';
        if ($credit == 'credit1') {
            //余额
            $buttion = '<button >提现</button>';
        } elseif ($credit == 'credit2') {
            //积分
            $buttion = '<a href="' . route("block.credit.recharge") . '"><button style="margin-right: 50px" >充值</button></a>'
                . '<a href="' . route("block.credit.transfer") . '"><button >转赠</button></a>';

            $help = '<div class="weifenTips">
                    <div class="name"><span class="fs28 fw_b color_r">积分获取方法</span></div>
                    <div class="list fs24 color_3">
                        <div>1.向推荐人购买(线下付款，推荐人转入)</div>
                        <div>2.联系客服购买</div>
                        <div>3.在线充值</div>
                    </div>
                    </div>';
        } elseif ($credit == 'credit3') {
            //DTC

        } elseif ($credit == 'credit4') {
            //推广收益
            $buttion = '<a href="' . route("block.credit.sell") . '"><button >出售</button></a>';
        } elseif ($credit == 'credit5') {
            //转存收益
            $buttion = '<a href="' . route("block.credit.sell") . '"><button >出售</button></a>';
        } elseif ($credit == 'credit6') {
            //团队收益
            $buttion = '<a href="' . route("block.credit.sell") . '"><button >出售</button></a>';
        }

        $html = [
            'credit' => $credit,
            'creditchinese' => CreditChinese($credit),
            'creditmount' => $user->$credit,
            'buttionnode' => $buttion,
            'help' => $help,
        ];
        $log = CreditLog::where(['uid' => $user->id, 'credit' => $credit])->get()->toArray();
        return view("credit.history", ['log' => $log, 'htmlvar' => $html]);
    }

    /**
     * 积分充值审核
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function recharge(Request $request)
    {

        $credit = "credit2";
        $user = $this->user->user();

        $p_uid = $this->officialuid;

        //官方指定用户支付宝和微信
        $paycode = $this->identity($p_uid, true);
        if ($request->ajax()) {
            $inputs = $request->only(['number', 'filepath']);

            if($p_uid == $user->id){
                return $this->error("您的账户是官方账户");
            }
            $review = CreditReview::InsertReview([
                'type' => 0,
                'p_uid' => $p_uid,
                'uid' => $user->id,
                'credit' => $credit,
                'number' => $inputs['number'],
                'pic' => $inputs['filepath'],
            ]);
            if ($review == null) {
                return $this->error("提交失败");
            }
            return $this->success("提交成功", route("block.credit.history", ['credit' => $currency]));
        }

        return view("credit.recharge", ['paycode' => $paycode]);
    }
    /**
     * 积分 转账
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function transfer(Request $request)
    {
        $credit = "credit2";
        $user = $this->user->user();
        if ($request->ajax()) {
            $inputs = $request->only(['phone', 'number']);
            if ($inputs['number'] >= $user->$credit) {
                return $this->error("积分不足");
            }
            if ($inputs['phone'] == $user->phone) {
                return $this->error("不能给自己转账");
            }
            $receive_user = User::where("phone", $inputs['phone'])->first();
            if ($receive_user == null) {
                return $this->error("提供的手机号不存在");
            }
            $foo = User::Transfer($user->id, $receive_user->id, $credit, $inputs['number'], [
                'f' => "主动转账给" . $inputs['phone'] . $inputs['number'],
                't' => "接收{$user->phone}转账" . $inputs['number'],
            ]);
            if (!$foo) {
                return $this->error("转账失败");
            }
            return $this->success("成功", route("block.credit.history", ['credit' => $credit]));
        }
        $htmlvar =[
            'jifen'=>$user->$credit
        ];
        return view("credit.transfer", ['htmlvar' => $htmlvar]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function sell(Request $request)
    {
        // 收益转存
        $type = 3;

        $user = $this->user->user();

        $okcredit = CreditChinese();
        unset($okcredit['credit1'],$okcredit['credit2'],$okcredit['credit3']);

        foreach ($okcredit as $create=>$name){
            $okcredit[$create] = $name . "(" . $user->$create.")";
        }

        if ($request->ajax()) {
            $inputs = $request->only(['number', 'cardid', 'credit']);
            $credit = $inputs['credit'];
            try {
                if (!in_array($credit, array_keys($okcredit))) {
                    return $this->error("您选择的资产无法出售");
                }
                if ($user->$credit <= $inputs['number']) {
                    return $this->error(CreditChinese($credit)."资产不足");
                }
                $card = $this->blockService->GetCardBetweentWorth($inputs['number'],$inputs['cardid']);
                if($card == null){
                    return $this->error("找不到对应的");
                }

                $find = Recording::CheckRecordingExist([
                    'card_id'=>$card->id,
                    'uid'=>$user->id,
                    'type'=>$type
                ]);

                if($find != null){
                    return $this->error("您今天已经提交过了");
                }
                $remark = CreditChinese($credit)."资产出售将转化为【" . $card->name ."】";
                User::CurrencyChange($user->id, $credit, -($inputs['number']), $remark);
                $recording = Recording::InsertRecording($card->toArray(),[
                    'release_uid' => 0,
                    'uid' => $user->id,
                    'batch_id' => 0,
                    'deduct_credit'=>$credit,
                    'deduct_amount'=>$inputs['number'],
                    'price'=>$inputs['number'],
                    'type' => $type,
                    'winbidding' => 1,
                    'winbidding_at'=>Carbon::now(),
                    'userhave' => 1,
                    'userhave_at'=>Carbon::now(),
                    'transfer_status' => 1,
                ]);
                //发布需要审核
                CardSell::NoCardSellSale([
                    'uid'=>$user->id,
                    'card_id' => $inputs['cardid'],
                    'recording_id'=>$recording->id,
                    'price' => $inputs['number'],
                ]);
                return $this->success("提交成功",route("block.credit.history",['credit'=>$credit]));
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        return View::make("credit.sell", ['okcredit' => $okcredit]);
    }




}
