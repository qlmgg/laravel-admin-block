<?php

namespace App\Admin\Actions\Block;

use App\Models\Block\Recording;
use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CardAssignUser extends RowAction
{
    public $name = '强刷宠物';
    public function form()
    {
        $this->mobile('uid_phone', "强制刷入手机号")->style("width","100%)")->rules('required');
        $this->text('init_price', '领养价值')->rules('required');
        $this->text('price', '当前价值')->rules('required');
        $this->text('daydown', '收益到期天数');
    }

    public function handle(Model $model,Request $request)
    {
        $inputs = $request->only("uid_phone","init_price",'price','daydown');
        // $model ...
        $user = User::where("phone",$inputs['uid_phone'])->first();
        if($user == null){
            return $this->response()->error("用户不存在");
        }
        Recording::create([
            'card_id'=> $model->id,
            'batch_id' => 0,
            'release_uid' => 0,
            'uid'=> $user->id,
//            "type"=> 3,//系统分配
            'deduct_currency' =>0,
            'deduct_amount'=>0,
            'init_price'=>$inputs['init_price'],
            'price' => $inputs['price'],
            'daydown'=>$inputs['daydown'] ?? $model->profit_day,
            'winbidding'=>1,
            'winbidding_at'=>Carbon::now(),
            'userhave' => 1,
            'userhave_at' => Carbon::now(),
        ]);
        return $this->response()->success('Success message.')->refresh();
    }

}
