<?php

namespace App\Admin\Controllers\Block;


use App\Jobs\Block\BatcheQueue;
use App\Models\Block\Recording;
use App\Models\Block\Batche;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\Log;


class BatcheController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '队列管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Batche());
//        $grid->model()->where("queue",0);
        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableDelete();
        });
        $grid->column('id', __('Id'));
        $grid->column('cycle', "队列唯一标识");
        $grid->column('status', "状态")->using([
            0 => "未启动",
            1 => "立即运行",
            2 => "执行结束",
        ]);

        $grid->column('queue', "队列执行状况")->using([
            0 => "未启动",
            1 => "执行结束",
        ]);
        $grid->column('created_at', "创建时间");
        return $grid;
    }


    public function show($id, Content $content)
    {


        return $content
            ->title("队列执行查看")
            ->row(function (Row $row) use ($id) {
                $row->column(5, function (Column $column) use ($id) {
                    //编辑
                    $form = new Form(new Batche());
                    $form->tools(function (Form\Tools $tools) {
                        $tools->disableDelete();
                        $tools->disableView();
                        $tools->disableList();
                    });

                    $form->footer(function ($footer) {
                        // 去掉`重置`按钮
                        $footer->disableReset();
                        // 去掉`查看`checkbox
                        $footer->disableViewCheck();
                        // 去掉`继续编辑`checkbox
                        $footer->disableEditingCheck();
                        // 去掉`继续创建`checkbox
                        $footer->disableCreatingCheck();
                        // 去掉`提交`按钮
                        $footer->disableSubmit();
                    });

                    $form->setAction(admin_url("block/batche/" . $id));

                    $form->text('cycle', "唯一编码")->disable();
                    $options = [
                        0 => "未启动",
                        1 => "立即运行",
                        2 => "执行结束",
                    ];
                    $form->select("status", "状态")->options($options)->disable();
                    $column->append($form->edit($id));

                });
                $row->column(7, function (Column $column) use ($id) {
                    //列表
                    $grid = new Grid(new Recording());
                    $grid->disableActions();
                    $grid->disableCreateButton();
                    $grid->disableRowSelector();
                    $grid->actions(function (Grid\Displayers\Actions $actions) {
                        $actions->disableView();
                        $actions->disableEdit();
                        $actions->disableDelete();
                    });
                    $grid->model()->where("batch_id", $id);
                    $grid->column('id', __('Id'));
                    $grid->column('card_id', "图鉴ID");
                    $grid->column('release_uid', "谁发布");
                    $grid->column('uid', "谁预约");
                    $grid->column('type', "1预约 2抢购");
                    $grid->column('created_at', "创建时间");

                    $column->append($grid);
                });

            });

    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Batche());
        $form->number('cycle', __('cycle'));
        $form->number('status', __('Status'));

        $form->saved(function (Form $form) {
            if ($form->status == 1) {
                dispatch(new BatcheQueue($form->model()->cycle));
                Log::debug($form->model()->cycle . "执行队列");
            }
        });
        return $form;
    }

}
