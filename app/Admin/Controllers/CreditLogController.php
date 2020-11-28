<?php

namespace App\Admin\Controllers;

use App\Models\CreditLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CreditLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '账户流水统计';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CreditLog());

        $grid->disableActions();
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();

        $grid->column('id', __('Id'))->sortable()->filter();

        $grid->column('credit', "币种")->display(function ($currency) {
            return CreditChinese($currency);
        });
        $grid->column('option', "操作");
        $grid->column('p_uid', "转账者")->filter();
        $grid->column('uid', "收款者")->filter();
        $grid->column('much', "金额");
        $grid->column('remark', "备注");
        $grid->column('created_at', "流水创建时间");
        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CreditLog());

        $form->text('credit', __('Credit'));
        $form->text('option', __('Option'));
        $form->number('uid', __('Uid'));
        $form->decimal('much', __('Much'));
        $form->text('remark', __('Remark'));

        return $form;
    }
}
