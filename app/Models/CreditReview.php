<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditReview extends Model
{
    //
    use  SoftDeletes;
    protected $fillable=[
        'type','mark','p_uid','uid','credit','number','pic','status'
    ];
    //  [0 => '充值',1 => '提现',2 => '转账']
    public static function InsertReview($data){
        return self::create($data);
    }
}
