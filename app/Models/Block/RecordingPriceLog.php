<?php

namespace App\Models\Block;

use Doctrine\DBAL\Schema\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use PHPUnit\Util\RegularExpressionTest;

class RecordingPriceLog extends Model
{
    //
    use SoftDeletes;

    //
    /**
     * @var string
     */
    protected $table = "block_recording_pirce_logs";

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'ymd',
        'recording_id',
        'uid',
        'credit',
        'much',
        'remark',
        'created_at'
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function (RecordingPriceLog $recordingPriceLog) {
            $recordingPriceLog->ymd = Carbon::now()->format("Ymd");
            // 合约货币
            $recordingPriceLog->credit = 'credit2';
            $recordingPriceLog->created_at = Carbon::now();
        });
    }

    /**
     *  每天产生的价值 计算公式
     * @param $Recording
     * @return float|int
     */
    public static function ymdPrice($Recording)
    {
        if (!$Recording['card']) {
            return 0;
        }
        $today_profit = $Recording['init_price'] * $Recording['card']['profit_rate'] / 100 / $Recording['card']['profit_day'];
        return CreditNumberFormat($today_profit,5);
    }

    /**
     *
     * @param $RecordingID
     * @return float|int
     */
    public static function YmdIncPrice($RecordingID)
    {
        $recording = Recording::with("card")->where([
            'id' => $RecordingID,
            'winbidding' => 1,
            'userhave' => 1,
            'transfer_status' => 0
        ])->first();
        if ($recording == null) {
            return null;
        }
        // 到期处理
        if (Recording::RecordingExpireToCardsell($recording)){
            return null;
        }
        // 今天收益
        $ymdnowprice = self::ymdPrice($recording);

        $ymdfindcount = self::where([
            'ymd' => Carbon::now()->format("Ymd"),
            'uid' => $recording->uid,
            'recording_id' => $recording->id,
        ])->count();
        if ($ymdfindcount > 0) {
            return null;
        }
        self::create([
            'recording_id' => $RecordingID,
            'uid' => $recording->uid,
            'much' => $ymdnowprice,
            'remark'=>"图鉴{$recording->card->name}合约收益"
        ]);

        //自动完成转存 和升级
        $AutoAture = RecordingGrowing::AutoCardUpgrade($recording->id, $ymdnowprice);
        if ($AutoAture) {
            return null;
        }
        // 正常收益
        Recording::SetDaydownPrice($recording->id, $ymdnowprice);
        return $ymdnowprice;
    }
}
