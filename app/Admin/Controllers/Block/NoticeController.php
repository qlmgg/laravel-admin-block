<?php

namespace App\Admin\Controllers\Block;

use App\Models\Block\Notice;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class NoticeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '通知公告管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Notice());

        $grid->column('id', __('Id'))->sortable()->filter();
//        $grid->column('type', __('Type'))->using([
//            1=>"系统通知",2=>"活动通知"
//        ]);
        $grid->column('title', __('标题'))->filter();
//        $grid->column('content', __('Content'));
        $grid->column('is_index', __('是否显示首页'))->using([
            0=>"不显示",1=>"显示"
        ]);
        $grid->column('created_at', __('创建时间'));

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
        $show = new Show(Notice::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'));
        $show->field('title', __('Title'));
        $show->field('content', __('Content'));
        $show->field('is_index', __('Is index'));
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
        $form = new Form(new Notice());

        $form->select("type","类型")->options([1 => '系统通知', 2 => '活动通知']);
        $form->text('title',"标题");
        $form->textarea('content', "内容");
        $form->switch('is_index', "是否在首页通知");

        return $form;
    }
}
