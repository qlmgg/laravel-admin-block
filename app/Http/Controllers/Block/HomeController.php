<?php

namespace App\Http\Controllers\Block;


use App\Models\Block\Batche;
use App\Models\Block\Card;
use App\Models\Block\CardSell;
use App\Models\Block\Notice;
use App\Models\Block\Recording;

use App\Models\User;
use Carbon\Carbon;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;


class HomeController extends BaseController
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view("home.index");
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function doindex()
    {
        $list = $this->blockService->Cardlist();
        return $this->success("获取数据成功", "", ["cardlist" => $list]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    function submit(Request $request)
    {

        $paycurrency = "credit2";

        $user = $this->user->user();

        $inputs = $request->only("index", "type", 'cardsell_id');
        $identity = $this->identity();
        if (!$identity) {
            return $this->error("还没实名认证审核");
        }


        $cardobj = Card::where([
            'id' => $inputs['index']
        ])->first();
        if ($cardobj == null) {
            return $this->error("记录不存在");
        }

        $card = $cardobj->toArray();
        if ($card['reserve_price'] - $user->$paycurrency > 10 ) {
            return $this->error("积分不足!");
        }

        if (empty($inputs['cardsell_id'])) {
            return $this->error("没有宠物可领取");
        }
        //预约
        if ($inputs['type'] == 1) {
            return $this->reservation($card);
        }
        //领养
        if ($inputs['type'] == 2) {
            return $this->snapup($card);
        }
        return $this->error("非法访问");
    }


    /**
     * 预约
     * @param $card
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    function reservation($card)
    {
        $paycredit = "credit2";
        // 1  预约
        //$type = request()->input("type");

        $cardsell_id = \request()->input("cardsell_id");
        $type = 1;
        $carbon = Carbon::now();
        try {
            $user = $this->user->user();
            $cache_key = $carbon->format("Ymd") . "_type_" . $type . "_cardid_" . $card['id'] . "_uid_" . $user->id;
            $cache_value = $card['id'] . "-" . $user->id;
            $cache_ttl = 24 * 60;
            $pay_money = $card['reserve_price'];
            if (Cache::has($cache_key)) {
                return $this->error("您已经预约过了");
            }

            $releaseObj = CardSell::where([
                'id' => $cardsell_id,
                'card_id' => $card['id'],
                'is_sale' => 1,
                'lock' => 0,
            ])->whereNotIn('uid', [$user['id']])->first();


            if ($releaseObj == null) {
                return $this->error("没有宠物可以领取");
            }

            $price = $releaseObj->price;
            $card_sell_id = $releaseObj->id;
            $release_uid = $releaseObj->uid;
            $release_recording_id = $releaseObj->recording_id;

            $yuyue = Recording::RecordingFirst([
                'card_id' => $card['id'],
                'uid' => $user->id,
                'type' => $type,
                'batch_id' => 0,
            ]);
            if ($yuyue != null) {
                return $this->error("您已经预约过了", "", $yuyue);
            }
            $recordingid = Recording::InsertRecording($card, [
                'card_sell_id' => $card_sell_id,
                'release_uid' => $release_uid,
                'uid' => $user->id,
                'batch_id' => 0,
                'price' => $price,
                'deduct_credit' => $paycredit,
                'deduct_amount' => $pay_money,
                'type' => $type,
            ]);

            $remark = "用户" . $user->name . "参与预约【{$card['id']}】" . $card['name'] . "，花费" . $pay_money . $paycredit;
            $usermoney = User::CurrencyChange($user->id, $paycredit, -$pay_money, $remark);
            if (!$recordingid || !$usermoney) {
                return $this->error("预约失败");
            }
            //24 小时失效
            Cache::put($cache_key, $cache_value, $cache_ttl);
            return $this->success("预约成功");
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error("预定失败");
        }
    }

    /**
     * 抢购(领养)
     * @param $card
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    function snapup($card)
    {

        $paycredit = "credit2";
        $pay_money = $card['adopt_price'];
        //抢购
        $type = 2;
        try {
            $cardsell_id = \request()->input("cardsell_id");

            $carbon = Carbon::now();
            $user = $this->user->user();
            $cache_key = $carbon->format("Ymd") . "_type_" . $type . "_cardid_" . $card['id'] . "_uid_" . $user->id;
            $cache_value = $card['id'] . "-" . $user->id;
            $cache_ttl = 24 * 60;
            if (Cache::has($cache_key)) {
                return $this->error("您已经领养过了");
            }
            $cycle = $carbon->format("YmdH") . "-" . $card['id'];

            $batchid = Batche::installOneId($cycle);


            $yuyue = Recording::RecordingFirst([
                'card_id' => $card['id'],
                'uid' => $user->id,
                'batch_id' => 0,
                'type' => 1,
            ]);


            if ($yuyue != null) {

                $releasesObj = CardSell::where([
                    'card_id' => $card['id'],
                    'is_sale' => 1,
                    'lock' => 0,
                ])->whereNotIn('uid', [$user['id']])->get();

                $releasearr = $releasesObj->toArray();
                $temp_card_sell_id = $yuyue->card_sell_id;
                $temp_card_sell_ids = [];
                array_walk($releasearr, function ($value, $key) use (&$temp_card_sell_ids) {
                    $temp_card_sell_ids[$value['id']] = $key;
                });
                if (!in_array($temp_card_sell_id, array_keys($temp_card_sell_ids))) {
                    //更新shell记录
                    $index = array_rand(array_values($temp_card_sell_ids));
                    $updaterelease = $releasearr[$index];
                    $recording = Recording::where('id', $yuyue->id)->update([
                        'card_sell_id' => $updaterelease['id'],
                        'release_uid' => $updaterelease['uid'],
                        'batch_id' => $batchid,
                    ]);
                    CardSell::where("id", $updaterelease['id'])->update(['lock' => 1]);
                    Recording::where("id", $updaterelease['recording_id'])->update(['transfer_status' => 2]);
                } else {
                    $release_recording_id = $releasearr[$temp_card_sell_ids[$temp_card_sell_id]]['recording_id'];
                    $recording = Recording::where('id', $yuyue->id)->update([
                        'batch_id' => $batchid,
                    ]);
                    CardSell::where("id", $temp_card_sell_id)->update(['lock' => 1]);
                    Recording::where("id", $release_recording_id)->update(['transfer_status' => 2]);
                }


            } else {

                $releaseObj = CardSell::where([
                    'id' => $cardsell_id,
                    'card_id' => $card['id'],
                    'is_sale' => 1,
                    'lock' => 0,
                ])->whereNotIn('uid', [$user['id']])->first();
                if ($releaseObj == null) {
                    return $this->error("没有宠物可以领取");
                }

                $price = $releaseObj->price;
                $card_sell_id = $releaseObj->id;
                $release_uid = $releaseObj->uid;
                $release_recording_id = $releaseObj->recording_id;


                $yuyue = Recording::RecordingFirst([
                    'card_id' => $card['id'],
                    'uid' => $user->id,
                    'type' => $type,
                ]);
                if ($yuyue != null) {
                    return $this->error("您已经领取过了", "", $yuyue);
                }

                $recording = Recording::InsertRecording($card, [
                    'card_sell_id' => $card_sell_id,
                    'release_uid' => $release_uid,
                    'uid' => $user->id,
                    'batch_id' => $batchid,
                    'price' => $price,
                    'deduct_credit' => $paycredit,
                    'deduct_amount' => $pay_money,
                    'type' => $type,//抢购
                ]);


                CardSell::where("id", $card_sell_id)->update(['lock' => 1]);
                Recording::where("id", $release_recording_id)->update(['transfer_status' => 2]);

                //扣钱
                $remark = "用户" . $user->name . "参与领养【{$card['id']}】" . $card['name'] . "，花费" . $pay_money . $paycredit;
                User::CurrencyChange($user->id, $paycredit, -$pay_money, $remark);
            }
            if (!$recording) {
                return $this->error("领养失败");
            }
            //当天加锁 24 小时失效
            Cache::put($cache_key, $cache_value, $cache_ttl);
            //定时访问用
            Cookie::queue('batchcardid_' . $card['id'], $batchid);
            return $this->success("领养成功", "", ['cardid' => $card['id']]);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->error("领养失败");
        }
    }

    /**
     * 抢购结果通知
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function snapupresult(Request $request)
    {
        $cardid = $request->input('cardid', 0);
        $batchid = $request->cookie('batchcardid_' . $cardid);
        $win = Recording::where([
            'batch_id' => $batchid,
            'card_id' => $cardid,
            'ymd' => Carbon::now()->format("Ymd"),
            'uid' => $this->user->id(),
            'winbidding' => 1,
            'userhave' => 0,
        ])->first();

        if ($win == null) {
            return $this->error("没有抽中");
        }
        $res = [
            'title' => "恭喜您抽中",
            'text' => $win->card->name,
            'src' => $this->blockService->FullImage($win->card->pic),
            'data' => 'batchcardid_' . $cardid,
        ];
        Cookie::forget('batchcardid_' . $cardid);
        return $this->success("结果获取", "", $res);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function announcement(Request $request)
    {
        $notice = Notice::where("is_index", 1)->first();
        return $this->success("获取数据成功", "", $notice);
    }

}
