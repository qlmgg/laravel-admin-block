<?php

namespace App\Admin\Actions;

use App\Models\User;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CurrencyForm extends RowAction
{
    public $name = '资金变动';

    public function form()
    {
        $allCredit = CreditChinese();
        foreach ($allCredit as $credit=>$name){
            $this->text($credit,$name);
        }
        $this->textarea('remark', '备注信息...');

    }

    public function handle(Model $model,Request $request)
    {
        $allCredit = CreditChinese();
        $Credits = array_keys($allCredit);
        $huobi = $request->only($Credits);
        foreach ($huobi as $currency =>$much){
            if($much !=null ){
                $option = $much > 0 ? '增加':'减少';
                $remark = $request->get("remark") ?? "系统管理员".$option.$currency.":".$much;
                User::CurrencyChange($model->id,$currency,$much,$remark);
            }
        }
        return $this->response()->success('操作成功')->refresh();
    }

}
