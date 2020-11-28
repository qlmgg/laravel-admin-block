<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Timing extends Model
{
    public $timestamps = false;

    //
    protected $fillable = [
        'jobname', 'dec', 'count', 'ymd','created_at'
    ];
    public static function RunsetInc($field = 'count', $number = 1,$createdata){
        $foo = self::where([
            'ymd' => $createdata['ymd'],
            'jobname' =>$createdata['jobname'],
        ])->increment($field, $number);
        if(!$foo){
            $foo = self::create(array_merge($createdata,['created_at'=>Carbon::now()]));
        }
        return $foo;
    }

}
