<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    //
    public function sendcode(Request $request)
    {
        $inputs = $request->only("phone");
        $validator = Validator::make($inputs, [
            'phone' => 'required|regex:/^1[0-9]{10}$/',
        ],[
            'phone.required'=>"手机号未填写",
            'phone.regex'=>"手机号不合法"
        ]);
        $error = $validator->errors()->first();
        if ($error){
            return ApiResult(0,$error);
        }
        $code=str_pad(rand(1,99999),6,"0",STR_PAD_LEFT);
        $cache_key = "sendcode_".$inputs['phone'];
        $res = SendPhoneCode($inputs['phone'],$code);
        if(!$res){
            return ApiResult(0,"发送失败");
        }
        $ttl = 60;
        Cache::put($cache_key,$code,$ttl);
        return ApiResult(1,"发送成功有效期1分钟",['codekey'=>$cache_key,'ttl'=>$ttl]);
    }
}
