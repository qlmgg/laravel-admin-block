<?php

namespace App\Admin\Controllers\Block;

use App\Models\Block\RecordingAppeal;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RecordingAppealController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '投诉列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new RecordingAppeal());
        $grid->disableActions();
        $grid->model()->with("recording");
        $grid->column('id', __('Id'))->sortable()->filter();
        $grid->column('recording_id', __('记录ID'))->filter();
        $grid->column('complaint_uid', __('原告用户ID'))->filter();
        $grid->column('accused_uid', __('被告用户ID'))->filter();
        $grid->column('reason', __('理由'));
        $grid->column('status', __('状态处理'))->radio([
            0 => '未处理',
            1 => '已处理',
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
        $show = new Show(RecordingAppeal::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('recording_id', __('Recording id'));
        $show->field('complaint_uid', __('Complaint uid'));
        $show->field('accused_uid', __('Accused uid'));
        $show->field('ymd', __('Ymd'));
        $show->field('reason', __('Reason'));
        $show->field('status', __('Status'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new RecordingAppeal());

        $form->number('recording_id', __('Recording id'));
        $form->number('complaint_uid', __('Complaint uid'));
        $form->number('accused_uid', __('Accused uid'));
        $form->number('ymd', __('Ymd'));
        $form->textarea('reason', __('Reason'));
        $form->switch('status', __('Status'));

        return $form;
    }
}
