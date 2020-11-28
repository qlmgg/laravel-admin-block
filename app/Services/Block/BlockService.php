<?php


namespace App\Services\Block;


use App\Models\Block\Card;
use App\Models\Block\CardSell;
use App\Models\Block\Recording;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class BlockService
{
    /**
     * @var mixed
     */
    public $user;


    /**
     * BlockService constructor.
     */
    public function __construct()
    {
        $this->user = User::modelGurd();
    }


    public function GetCardBetweentWorth($worth, $id = 0)
    {
        $where = [
            ['min_worth_price', '<=', $worth],
            ['max_worth_price', '>=', $worth],
        ];
        $method = "get";
        if ($id) {
            $where = array_merge($where, [
                ['id', '=', $id]
            ]);
            $method = 'first';
        }
        return Card::where($where)->$method();
    }

    /**
     * @param $img
     * @return string
     */
    public function FullImage($img)
    {
        return config("filesystems.disks.admin.url") . $img;
    }


    /** 图鉴列表
     * @return mixed
     */
    public function Cardlist()
    {
        $list = Card::select([
            'id', 'name', 'levelname', 'begin_time', 'end_time', 'pic',
            'min_worth_price', 'max_worth_price', 'reserve_price',
            'adopt_price', 'profit_day', 'profit_rate',
            'bestow1', 'bestow3'
        ])->get();
        if ($list == null) {
            return [];
        }
        $cardlist = $list->toArray();
        foreach ($cardlist as $k => $card) {
            $item['id'] = $card['id'];
            $item['name'] = $card['name'];
            $item['begin_time'] = $card['begin_time'];
            $item['end_time'] = $card['end_time'];
            $item['begin_time_int'] = strtotime($card['begin_time']);
            $item['end_time_int'] = strtotime($card['end_time']);
            $item['pic'] = $this->FullImage($card['pic']);
            $item['cardsell_id'] = $this->GetCardReleaseUser($card);
            $item['statusinfo'] = $this->CardStatusInfo($item);
            $item['htmlinfo'] = $this->HtmlInfoNode($card);
            $cardlist[$k] = $item;
        }
        return $cardlist;
    }

    public function HtmlInfoNode($card)
    {
        $jiazhi = CreditNumberFormat($card['min_worth_price'], 2) . '~' . CreditNumberFormat($card['max_worth_price'], 2);
        $lingyangshijian = $card['begin_time'] . '-' . $card['end_time'];
        $jifen = CreditNumberFormat($card['reserve_price'], 2) . '/' . CreditNumberFormat($card['adopt_price'], 2);
        $heyue = $card['profit_day'] . '天/' . $card['profit_rate'] . "%";
        $dengji = $card['levelname'];
        $html = "<div>价值:<span>{$jiazhi}</span></div>"
            . "<div>领养时间:<span>{$lingyangshijian}</span></div>"
            . "<div>预约/即抢领养积分:<span>{$jifen}</span></div>"
            . "<div>预约/即抢领养积分:<span>{$heyue}</span></div>"
            . "<div>等级:<span>{$dengji}</span></div>"
            . $this->wahtml($card);
        return $html;
    }

    public function wahtml($card)
    {
        $wahtml = '';
        $shouyicredit = CardBestow2credit();
        foreach ($shouyicredit as $b => $c) {
            $cc = CreditChinese($c);
            $baifen = $card[$b];
            $wahtml .= "<div>可挖{$cc}:<span>{$baifen}</span></div>";
        }
        return $wahtml;
    }


    /**
     *  1 预约
     *  2 倒计时
     *  3 领养
     *  4 繁殖中
     *  5 待领养
     * @param $card
     * @return array
     */
    public function CardStatusInfo(&$card)
    {

        $now = Carbon::now();
        $begin_time_timestamp = Carbon::parse($card['begin_time'])->timestamp;
        $end_time_timestamp = Carbon::parse($card['end_time'])->timestamp;
        // 可以领取的时间差
        $keyilingqu = $now->timestamp >= $begin_time_timestamp && $now->timestamp <= $end_time_timestamp;
        $b_surplus = $begin_time_timestamp - $now->timestamp;
        $e_surplus = $now->timestamp - $end_time_timestamp;

        $statusint = 0;
        $daojishi = 90;


        $yuyueHourminute = 60;
        $s = Carbon::now()->startOfHour();
        $e = Carbon::now()->startOfHour()->addMinute($yuyueHourminute);
        $yuyuetedingshijian = $now->timestamp >= $s->timestamp && $now->timestamp <= $e->timestamp;

        $info = '';

        if ($b_surplus > 0) {
            //预约
            $statusint = 1;
        }
        if ($b_surplus <= $daojishi) {
            //(倒计时)
            $statusint = 2;
        }
        //进入可以领养的时间范围内
        if ($b_surplus < 0) {
            //领养
            $statusint = 3;
            if ($e_surplus >= 0 || !$card['cardsell_id'] ) {
                //繁殖中
                $info = "结束时间后";
                $statusint = 4;
            }
        }
        if ($now->timestamp >= $end_time_timestamp) {
//        if ($now->timestamp >= $end_time_timestamp || !$card['cardsell_id']) {
            //繁殖中
            $statusint = 4;
            $info = "现在时间大于结束时间";
        }

        //预约过的显示待领取
        $yuyueRecording = Recording::where([
            'card_id' => $card['id'],
            'uid' => $this->user->id(),
            'ymd' => Carbon::now()->format("Ymd"),
            'type' => 1,
            'winbidding' => 0,
        ])->count();
        //中标
        $winrecordingcount = Recording::where([
            'card_id' => $card['id'],
            'uid' => $this->user->id(),
            'ymd' => Carbon::now()->format("Ymd"),
            'winbidding' => 1,
            'userhave' => 0,
        ])->count();

        // 预约/中标 显示待领养
        if ($yuyueRecording  || $winrecordingcount) {
            $statusint = 5;
            if($keyilingqu){
                $statusint = 3;
            }
        }

        return [
            'index' => $card['id'],
            'cardsell_id' => $card['cardsell_id'],
            'info' => $info,
            'status' => $statusint,
            'begin_diff' => $b_surplus,
            'end_diff' => $e_surplus
        ];
    }

    /**
     * 获取发布人信息
     * @param $card
     * @return array|mixed
     */
    public function GetCardReleaseUser($card)
    {
        $user = $this->user->user()->toArray();
        $usersell = CardSell::inRandomOrder()->select(['id'])->where([
            'card_id' => $card['id'],
            'is_sale' => 1,
            'lock' => 0,
        ])->whereNotIn('uid', [$user['id']])->first();
        if ($usersell == null) {
            return 0;
        }
        return $usersell['id'];
    }


}
