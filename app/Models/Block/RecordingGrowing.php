<?php

namespace App\Models\Block;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class RecordingGrowing extends Model
{
    use SoftDeletes;

    //
    /**
     * @var string
     */
    protected $table = "block_recording_growings";

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'recording_id',
        'old_card_id',
        'new_card_id',
        'ymd',
        'created_at',
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function (RecordingGrowing $recordingGrowing) {
            $recordingGrowing->ymd = Carbon::now()->format("Ymd");
            $recordingGrowing->created_at = Carbon::now();
        });
    }

    /**
     *  收益自动转存 自动完成升级
     * @param $recordingId
     * @param $price
     * @return int
     * @throws \Exception
     */
    public static function AutoCardUpgrade($recordingId, $ymdnowprice)
    {
        $recording = Recording::with("card")->where("id", $recordingId)->first();
        if ($recording == null) {
            return 0;
        }
        $worth_price = $recording->price + $ymdnowprice;
        $oldCardId = $recording->card_id;
        //当前价值减去 所有卡片的最大值 如果是正 就把余数 作为合约自动转存
        $maxprice = Card::max('max_worth_price');
        $credit2 = $worth_price - $maxprice;
        if ($credit2 > 0) {
            User::CurrencyChange($recording->uid, "credit2", $credit2, "满级合约自动转存");
            return 2;
        }
        $findcount = self::where([
            'ymd' => Carbon::now()->format("Ymd"),
            'recording_id' => $recording->id,
        ])->count();
        if ($findcount > 0) {
            return 0;
        }
        $min_worth_price = $recording->card->min_worth_price;
        $max_worth_price = $recording->card->max_worth_price;
        // 判断是否在区间之内
        $foo = version_compare($worth_price, $min_worth_price, '>=') and version_compare($worth_price, $max_worth_price, '<=');
        if ($foo) {
            return 0;
        }
        // 不在区间之内就去生成新的
        $fullCard = Card::where('min_worth_price', '<=', $worth_price)->where("max_worth_price", ">=", $worth_price)->first();
        if ($fullCard == null){
            return 0;
        }
        $newCardId = $fullCard->id;
        // 保存记录 生成记录
        self::create([
            'recording_id' => $recordingId,
            'old_card_id' => $oldCardId,
            'new_card_id' => $newCardId
        ]);
        Recording::where("id", $recordingId)->update([
            'card_id' => $newCardId
        ]);
        return 0;
    }
}
