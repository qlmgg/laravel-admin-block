<?php

namespace App\Admin\Actions\Block\Settings;

use App\Models\Config;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class Sms extends Form
{
    public $title = 'www.17int.cn短信配置';

    private $configKey = "BLOCK17INTCNSMS";

    public function form()
    {
        $this->hidden("key")->default($this->configKey);
        $this->text('desc', __('描述'))->default("www.17int.cn短信配置");
        $this->text('value[sign]', __('签名'));
        $this->text('value[account]', __('账号'));
        $this->text('value[password]', __('密码'));
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
        $c =  Config::GetKeyValue($this->configKey);
        return [
            'key'=>$c->key,
            'desc'=>$c->desc,
            "value[sign]"=>$c->value['sign'],
            "value[account]"=>$c->value['account'],
            "value[password]"=>$c->value['password'],
        ];
    }
}
