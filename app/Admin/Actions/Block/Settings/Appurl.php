<?php

namespace App\Admin\Actions\Block\Settings;

use App\Models\Config;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class Appurl extends Form
{
    public $title = 'app下载地址';

    private $configKey = "BLOCKAPPURL";

    public function form()
    {
        $this->hidden("key")->default($this->configKey);
        $this->text('desc', __('描述'))->default("app下载地址");
        $this->url("value", __("app下载地址"));
//        $this->url("value['ios']", __("IOS下载地址"));
//        $this->url("value['android']", __("android下载地址"));

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
