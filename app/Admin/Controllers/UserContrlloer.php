<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\CurrencyForm;
use App\Admin\Actions\MoneyOption;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserContrlloer extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->actions(function ($actions) {
            // 去掉查看
            $actions->disableView();
            $actions->add(new CurrencyForm());
        });
        $grid->filter(function ($filter) {
            // 在这里添加字段过滤器
            $filter->like('phone', '手机号');
        });

        $grid->column('id', "Uid【邀请码】")->sortable()->filter();

        $grid->column('parent_id', "上级ID")->filter();

        $grid->column('name', "称呼")->filter();

        $grid->column('phone', "手机号")->filter();

        $allCredit = CreditChinese();
        $grid->column(implode("/", $allCredit))->display(function () use (&$allCredit) {
            $str = '';
            foreach ($allCredit as $credit => $cname) {
                $str .= CreditNumberFormat($this->$credit) . "/";
            }
            return $str;
        });

        $grid->column('created_at', "创建时间");

        $grid->column('status', "状态");


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('parent_id', __('Parent id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('phone', __('Phone'));
        $show->field('phone_verified_at', __('Phone verified at'));
        $show->field('password', __('Password'));
        $show->field('order', __('Order'));
        $show->field('status', __('Status'));
        $show->field('remember_token', __('Remember token'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        if ($form->isEditing()) {
            $form->number('parent_id', "上级id")->default(0)->disable();
        } else {
            $form->number('parent_id', "上级id")->default(0);
        }
        $form->html("<span style='color:red;'>由于上级ID关联收益，不支持编辑</span>");


        $form->text('name', "称呼");
        $form->mobile('phone', "手机号")->required();
        $form->hidden('email')->default(rand() . "@zqw.xyz");
        $form->password('password', "密码")->required();
        $form->text('realname', "真实姓名");
        $form->text('idcard', "身份证号");
        $form->radio("status", "状态")->options([
            -1 => '待激活',
            0 => '正常',
            1 => '账号冻结'
        ])->default(0);
        //保存后回调
        $form->saving(function (Form $form) {
            //密码进行md5加密
            $form->password = PwdEncrypt($form->password);
        });
        return $form;
    }
}
