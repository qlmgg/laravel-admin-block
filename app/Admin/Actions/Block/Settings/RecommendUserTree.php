<?php

namespace App\Admin\Actions\Block\Settings;

use App\Models\Config;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class RecommendUserTree extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '推荐分销设置';

    private $configKey = "RECOMMENDUSERTREE";
    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        $inputs = $request->only(["key","desc",'value']);
        Config::SetKeyValue($inputs['key'],$inputs);
        admin_success('Processed successfully.');
        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->hidden("key")->default($this->configKey);
        $this->text('desc', __('描述'))->default("推荐收益分销设置");
        $this->table('value',"参数设置", function ($table) {
            $table->rate('scale',"比例分配");
        });
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
