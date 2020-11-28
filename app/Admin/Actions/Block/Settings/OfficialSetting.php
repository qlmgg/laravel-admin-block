<?php

namespace App\Admin\Actions\Block\Settings;

use App\Models\Config;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class OfficialSetting extends Form
{
    public $title = '官方区块配置中心';

    private $configKey = "BLOCKOFFICIALSETTING";

    public function form()
    {
        $this->hidden("key")->default($this->configKey);
        $this->text('desc', __('描述'))->default("官方指定配置");

        $this->number('value', __('默认收款UID'));
        $html = "<div style='color: red'>"
            . "设置前，请完成对这个用户实名认证以及支付方式审核方式</br>"
            . "作用："
            . "P2P积分充值</br>"
            . "预约/抢购宠物时，没有下放，此UID作为默认的收款账号</br>"
            . "</div>";
        $this->html($html);
    }

    public function handle(Request $request)
    {
        $inputs = $request->only(["key", "desc", 'value']);
        Config::SetKeyValue($inputs['key'], $inputs);
        admin_success('Processed successfully.');
        return back();
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        return Config::GetKeyValue($this->configKey);
    }

}
