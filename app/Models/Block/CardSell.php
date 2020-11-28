<?php

namespace App\Models\Block;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardSell extends Model
{
    //
    use SoftDeletes;
    //
    protected $table="block_card_sells";

    protected $fillable =[
        'uid',
        'recording_id',
        'card_id',
        'price',
        'is_sale',
        'order',
        'status'
    ];

    public static function NoCardSellSale($res){
        $data['uid'] =$res['uid'];
        $data['card_id'] = $res['card_id'];
        $data['recording_id'] = $res['recording_id'];
        $data['price'] = $res['price'];

        $data['is_sale'] = 0;
        return self::InsertCardSell($data);
    }
    public static function CardSellSale($res){
        $data['uid'] =$res['uid'];
        $data['card_id'] = $res['card_id'];
        $data['recording_id'] = $res['recording_id'];
        $data['price'] = $res['price'];
        $data['is_sale'] = 1;
        return self::InsertCardSell($data);
    }

    private static function InsertCardSell($data){
        return self::create($data);
    }
    function card(){
        return $this->belongsTo(Card::class,"card_id");
    }
    function recording(){
        return $this->belongsTo(Recording::class,"recording_id");
    }
}
