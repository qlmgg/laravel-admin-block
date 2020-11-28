<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\IdentityReview;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;

class IdentityController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '身份审核';


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new IdentityReview());
        $grid->model()->orderBy("way");

        $way = IdentityWayChinese();
        $grid->selector(function (Grid\Tools\Selector $selector) use (&$way) {
            $selector->select('way', '认证方式',$way);
            $selector->select('status', '认证状态', [
                -1=>"认证失败",0=>"未认证",1=>"通过认证"
            ]);
        });
        $grid->disableActions();
        $grid->column('id', __('Id'))->sortable()->filter();
        $grid->column('uid', __('Uid'));
        $grid->column('way', __('认证方式'))->using($way);
        $grid->column('realname', "姓名");
        $grid->column('phone', "手机号")->filter();
        $grid->column('account', "账户");
        //$grid->column('image', "认证提交图片")->image(config("filesystems.disks.admin.url"), 100, 100);
        $grid->image("认证图片")->gallery(['width' => 100, 'height' => 100]);
        $grid->column('status', __('状态处理'))->radio([
            -1 => '认证失败',
            0 => '等待认证',
            1 => '通过认证',
        ])->filter([
            -1 => '认证失败',
            0 => '等待认证',
            1 => '通过认证',
        ]);
        return $grid;
    }



    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new IdentityReview());

        $form->number('uid', __('Uid'));
        $form->number('way', __('Way'));
        $form->text('realname', __('Realname'));
        $form->number('phone', __('Phone'));
        $form->text('account', __('Account'));
        $form->image('image', __('Image'));
        $form->datetime('pass_at', __('Pass at'))->default(date('Y-m-d H:i:s'));
        $form->switch('status', __('Status'));
        return $form;
    }
}
