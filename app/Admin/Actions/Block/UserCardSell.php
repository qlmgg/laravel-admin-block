<?php

namespace App\Admin\Actions\Block;

use App\Models\Block\Recording;
use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserCardSell extends RowAction
{
    public $name = '下放宠物';

    public function form()
    {
        $this->mobile('release_phone', "下放指定用户手机号")->style("width","100%)")->rules('required');
        $this->text('price', '下放价格')->rules('required');
    }


    public function handle(Model $model,Request $request)
    {
        // $model ...
        $inputs = $request->only("release_phone",'price');

        // 先给生成一条Recording记录
        $release_user = User::where("phone",$inputs['release_phone'])->first();
        if($release_user == null){
            return $this->response()->error("转让方".$inputs['phone']."用户不存在");
        }

        $recording = Recording::create([
            'card_id'=> $model->id,
            'batch_id' => 0,
            'release_uid' => 0,
            'uid'=> $release_user->id,
//            "type"=> 3,//系统分配
            'deduct_currency' =>0,
            'deduct_amount'=>0,
            'init_price'=>$inputs['price'],
            'price' => $inputs['price'],
            'daydown'=>$model->profit_day,
            'winbidding'=>1,
            'winbidding_at'=>Carbon::now(),
            'userhave' => 1,
            'userhave_at' => Carbon::now(),
            'transfer_status'=>1,
        ]);

        \App\Models\Block\CardSell::create([
            'uid' => $release_user->id,
            'card_id' => $model->id,
            'is_sale' => 1,
            'recording_id'=>$recording->id,
            'price' => $inputs['price'],
        ]);

        return $this->response()->success('Success message.')->refresh();
    }

}
