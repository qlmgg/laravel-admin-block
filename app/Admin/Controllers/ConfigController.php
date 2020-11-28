<?php

namespace App\Admin\Controllers;

use App\Models\Config;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\Config';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Config());
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });
        $grid->column('id', __('Id'));
        $grid->column('key', __('唯一标识'));
        $grid->column('desc', __('描述'));
        $grid->column('value', __('参数序列化'));

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
        $show = new Show(Config::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('key', __(''));
        $show->field('desc', __('Desc'));
        $show->field('value', __('Value'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Config());

        $form->text('key', __('唯一标识'));
        $form->text('desc', __('描述'));
        $form->table('value',"参数设置", function ($table) {
            $table->text('const',"常量");
            $table->text('var',"变量");
        });

        return $form;
    }
}
