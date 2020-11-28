<?php

namespace App\Models\Block;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\RegularExpressionTest;

class Recording extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'card_id',
        'batch_id',
        'ymd',
        "hashblock",
        'card_sell_id',
        'release_uid',
        'uid',
        "type",
        'deduct_credit',
        'deduct_amount',
        'refund',
        'hashblock',
        'init_price',
        'price',
        'daydown',
        'winbidding',
        'winbidding_at',
        'userhave',
        'userhave_at',
        'transfer_status',
    ];
    //
    /**
     * @var string
     */
    protected $table = "block_recordings";

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $recording) {
            $hashblock = [
                'index' => $recording->uid,
                'timestamp' => Carbon::now()->timestamp,
                'transactions' => [
                    'card_id' => $recording->card_id,
                ]
            ];
            $recording->hashblock = hash("fnv164", serialize($hashblock));
            $recording->ymd = Carbon::now()->format("Ymd");
            $recording->init_price = $recording->price;
        });
    }

    //1 预约 2 抢购 3系统分配 4 收益转换
    public static function InsertRecording($card,$other = []){
        $carddata = [
            'card_id'=>$card['id'],
            'daydown'=>$card['profit_day'],
        ];
        $data = array_merge($carddata,$other);
        return self::create($data);
    }
    public static function RecordingFirst($w){
        $d =[
            'ymd'=>Carbon::now()->format("Ymd"),
        ];
        $where = array_merge($d,$w);
       return self::where($where)->first();
    }
    /**
     * @param $recordingID
     * @param $number
     * @param string $field
     * @return mixed
     */
    public static function SetDaydownPrice($recordingID, $number, $field = 'price')
    {
//        $find = self::where('id', $recordingID)->first();
//        if(self::isExpireDo($find)){
//            return 0;
//        }
        return self::where('id', $recordingID)->update([
            'daydown' => DB::raw('daydown - 1'),
            $field => DB::raw($field . ' + ' . $number),
        ]);

    }

    /**
     * 判断 Recording 是否到期，到期了 当前用户进入带转让区
     * @param $recording
     * @return bool
     */
    public  static  function RecordingExpireToCardsell($recording){

        if ($recording->daydown <= 0) {
             self::where('id', $recording->id)->update([
                'transfer_status' => 1
            ]);
             //到期发布不需要审核
            CardSell::CardSellSale([
                'uid'=>$recording->uid,
                'recording_id'=>$recording->id,
                'price'=>$recording->price,
                'card_id'=>$recording->card_id,
            ]);
           return true;
        }
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function batch()
    {
        return $this->belongsTo(Batche::class, "batch_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card()
    {
        return $this->belongsTo(Card::class, "card_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function releaseuser()
    {
        return $this->belongsTo(User::class, "release_uid");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, "uid");
    }
}
