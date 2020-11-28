<?php

namespace App\Admin\Controllers;

use App\Models\CreditReview;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CreditReviewController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '充值审核记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CreditReview());

        $grid->disableCreateButton();
        $grid->disableActions();

        $grid->column('id', __('Id'));
        $grid->column('type', "类型")->using([
            0 => '充值',
            1 => '提现',
            2 => '转账',
        ])->filter([
            0 => '充值',
            1 => '提现',
            2 => '转账',
        ]);
        $grid->column('mark', __('标记'));
        $grid->column('p_uid', __('转账人Uid'));
        $grid->column('uid', __('充值用户UId'));
        $grid->column('credit', __('充值货币'));
        $grid->column('number', __('充值金额'));
        $grid->pic("凭证")->gallery(['width' => 100, 'height' => 100]);

        $grid->column('created_at', __('创建时间'));
        $grid->column('status', __('状态处理'))->radio([
            0 => '等待处理',
            1 => '处理完成',
        ]);

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
        $show = new Show(CreditReview::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'));
        $show->field('mark', __('Mark'));
        $show->field('p_uid', __('P uid'));
        $show->field('uid', __('Uid'));
        $show->field('credit', __('Credit'));
        $show->field('number', __('Number'));
        $show->field('pic', __('Pic'));
        $show->field('status', __('Status'));
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
        $form = new Form(new CreditReview());

        $form->number('type', __('Type'));
        $form->text('mark', __('Mark'));
        $form->number('p_uid', __('P uid'));
        $form->number('uid', __('Uid'));
        $form->text('credit', __('Credit'));
        $form->decimal('number', __('Number'))->default(0.00000);
        $form->image('pic', __('Pic'));
        $form->number('status', __('Status'));

        return $form;
    }
}
