<?php

namespace App\Http\Controllers\Block;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\User;
use App\Models\IdentityReview;
use App\Services\Block\BlockService;
use Illuminate\Http\Request;

class BaseController extends Controller
{


    /**
     * @var mixed
     */
    public  $user;

    public $officialuid;
    /**
     * @var BlockService
     */
    public $blockService;
    /**
     * BaseController constructor.
     */
    public function __construct(){
        $this->blockService = new BlockService();
        $this->user=$this->blockService->user;
        $this->officialuid= Config::GetKeyValue("BLOCKOFFICIALSETTING","value") ?? 1;
        unset($this->user->password);
    }

    /**
     * @param int $uid
     * @param bool $payway
     * @return |null
     */
    function  identity($uid = 0, $payway=false){
        if (!$uid){
            $uid = $this->user->id();
        }
        $shenfen =IdentityReview::where(['uid'=>$uid,'status'=>1])->get()->toArray();
        if(!empty($shenfen)){
            foreach ($shenfen as $k => $v) {
                $shenfen[$k]['image'] = $this->blockService->FullImage($v['image']);
                $shenfen[$k]['wayinfo'] = IdentityWayChinese($v['way']);
                if($payway){
                    if($v['way'] == 0){
                        unset($shenfen[$k]);
                    }
                }
            }
            return $shenfen;
        }
        return  null;
    }


    /**
     * 判断变量是否在范围内
     *
     * @param string $strnum 1.1.1.1
     * @param string $min
     * @param string $max
     * @return bool
     */
    function number_segment_between($strnum, $min, $max)
    {
        return version_compare($strnum, $min, '>=') and version_compare($strnum, $max, '<=');
    }


    /**
     * @param string $msg
     * @param null $url
     * @param string $data
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function success($msg = '', $url = null, $data = ''){
        if(\request()->ajax() || \request()->post()){
            if(is_null($url) && is_null(request("HTTP_REFERER")) ){
                $url = request("HTTP_REFERER");
            }
            return response()->json([
                'code' => 1,
                'msg'  => $msg,
                'data' => $data,
                'url'  => $url,
            ]);
        }else{
            throw new \Exception("Non-AJAX && POST commit");
        }

    }

    /**
     * @param string $msg
     * @param null $url
     * @param string $data
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function error($msg = '', $url = null, $data = ''){

        if(\request()->ajax() || \request()->post()){
            if(is_null($url)){
                $url = "javascript:history.back(-1);";
            }
            return response()->json([
                'code' => 0,
                'msg'  => $msg,
                'data' => $data,
                'url'  => $url,
            ]);
        }else{
            throw new \Exception("Non-AJAX && POST commit");
        }
    }
}
