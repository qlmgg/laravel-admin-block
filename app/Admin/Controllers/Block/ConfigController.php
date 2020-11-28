<?php

namespace App\Admin\Controllers\Block;

use App\Admin\Actions\Block\Settings\Appurl;
use App\Admin\Actions\Block\Settings\ContractUserTree;
use App\Admin\Actions\Block\Settings\OfficialSetting;
use App\Admin\Actions\Block\Settings\RecommendUserTree;
use App\Admin\Actions\Block\Settings\Sms;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Tab;

class ConfigController extends AdminController
{

    public function index(Content $content)
    {
        $forms = [
            'official' => OfficialSetting::class,
            'sms' => Sms::class,
            'app' => Appurl::class,
            'basic' => ContractUserTree::class,
        ];

        return $content
            ->title('系统设置')
            ->body(Tab::forms($forms));
    }

}
