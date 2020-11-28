<?php

namespace App\Http\Controllers\Block;

use App\Models\Block\Card;
use Illuminate\Http\Request;

class CardController extends BaseController
{


    public function index(){
        dd("card");
    }
    public function worth(Request $request){
        $inputs =$request->only("worth");
        $full = $this->blockService->GetCardBetweentWorth($inputs['worth']);
        return $this->success("获取符合数据成功","",$full);
    }

}
