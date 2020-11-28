<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditLog extends Model
{

    use  SoftDeletes;
    //
    protected $fillable=[
        'credit','option','p_uid','uid','much','remark'
    ];

    public static function Insertlog($data){
        return self::create($data);
    }

}
