<?php


if (!function_exists("ClientInfo")) {
    function ClientInfo()
    {
        $agent = new Jenssegers\Agent\Agent();
        $device = $agent->device();
        $system = $agent->platform();
        $version = $agent->version($system);
        $browser = $agent->browser();
        $ip = \Illuminate\Support\Facades\Request::ip();
        return $device . $system . $version . $browser . $ip;
    }
}


if (!function_exists("CreditChinese")) {
    function CreditChinese($credit = null)
    {
        $creditarr = [
            "credit1" => "USDT",
            'credit2' => "积分",
            'credit3' => "DTC",
            'credit4' => "推广收益",
            'credit5' => "转存收益",
            'credit6' => "团队收益"
        ];
        return $credit == null ? $creditarr : $creditarr[$credit];
    }
}


if (!function_exists("IdentityWayChinese")) {
    function IdentityWayChinese($way = null)
    {
        $allway = [
            0 => "身份验证",
            1 => "支付宝",
            2 => "微信",
            3 => "银行卡",
        ];
        return $way == null ? $allway : $allway[$way];
    }
}

if (!function_exists("RecordingTypeChinese")) {
    function RecordingTypeChinese($type)
    {
        $typeChinese = [
            0 => "系统分配",
            1 => '预约',
            2 => '抢购',
            3 => '收益转换',
        ];
        return $typeChinese[$type];
    }
}

if (!function_exists("CreditNumberFormat")) {
    function CreditNumberFormat($credit, $decimals = 3)
    {
        return number_format($credit, $decimals);
    }
}

if (!function_exists("CardBestow2credit")) {
    function CardBestow2credit()
    {
        $bestowArr = [
            'bestow1' => 'credit1',
            'bestow3' => 'credit3'
        ];
        return $bestowArr;
    }
}

if (!function_exists("PwdEncrypt")) {
    function PwdEncrypt($var, $ecrypt = false)
    {
        if ($ecrypt) {
            return $var;
        }
        return md5($var);
    }
}


if (!function_exists("ApiResult")) {
    function ApiResult($code, $msg, $data = [])
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return \Illuminate\Support\Facades\Response::json($result);
    }
}

if (!function_exists("SendBlockRecordNotice")) {
    function SendBlockRecordNotice($phone, $type)
    {
        if ($type == 1) {
            $content = "尊敬的用户，您的订单状态有变化，请尽快登录系统进行处理，否则将自动转入非正常账户。";
        } else {
            $content = "尊敬的用户，您有宠物订单被领养，请尽快登录系统进行处理，否则2小时后将自动交易。";
        }
        return SendSMS($phone, $content);
    }
}


if (!function_exists("SendCode")) {
    function SendPhoneCode($phone, $code)
    {
        $content = '您的验证码是:' . $code . '验证码5分钟后过期，请您及时验证！';
        return SendSMS($phone, $content);
    }
}

if (!function_exists("SendSMS")) {
    function SendSMS($phone, $content)
    {
        if (mb_strlen($phone) != 11) {
            \Illuminate\Support\Facades\Log::error("SendSMS " . $phone . " ERROR: Length is not 11");
            return 0;
        }
//        $conf = [
//            'sign' => "",
//            'account' => '',
//            'password' => ''
//        ];
        $conf= \App\Models\Config::GetKeyValue("BLOCK17INTCNSMS","value");
        $client = new \GuzzleHttp\Client();
        $resp = $client->request("POST", "http://www.17int.cn/xxsmsweb/smsapi/send.json", [
            'headers' => [
                'Content-Type: application/json'
            ],
            'json' => [
                'account' => $conf['account'],
                'password' => strtoupper(md5($conf['password'])),
                'mobile' => $phone,
                'content' => "【{$conf['sign']}】" . $content,
                'requestId' => '523491875',
                'extno' => ''
            ]
        ]);
        $content = $resp->getBody()->getContents();
        $apiArr = json_decode($content, true);
        if ($apiArr['errorCode'] != 'ALLSuccess') {
            \Illuminate\Support\Facades\Log::error("SendSMS " . $phone . " ERROR:" . $content);
            return 0;
        }
        return 1;
    }
}
