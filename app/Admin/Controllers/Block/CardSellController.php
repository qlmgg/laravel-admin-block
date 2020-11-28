<?php

namespace App\Admin\Controllers\Block;

use App\Models\Block\CardSell;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CardSellController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '可销售审核排序';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CardSell());


        $grid->disableActions();
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();

        $grid->model()->with(["card","recording"])->where("lock",0);

        $grid->column('id', __('Id'))->sortable()->filter();
        $grid->column('recording_id', __('记录ID'))->filter();
        $grid->column('recording.hashblock', __("hasblcok"));
        $grid->column('uid', __('发布用户ID'))->filter();
        $grid->column('card_id', __('图鉴ID'))->filter();
        $grid->column('price', __('销售价格'))->sortable();
//        $grid->column('is_sale', __('Is sale'));
        $grid->column('order', __('排序（编辑）'))->editable();
//        $grid->column('lock', __('Lock'));

        $grid->column('is_sale', __('出售状态处理'))->radio([
            0 => '等待通过',
            1 => '通过出售',
        ])->filter([
            0 => '等待通过',
            1 => '通过出售',
        ]);

        $grid->column('created_at', __('提交时间'));


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
        $show = new Show(CardSell::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('recording_id', __('Recording id'));
        $show->field('uid', __('Uid'));
        $show->field('card_id', __('Card id'));
        $show->field('price', __('Price'));
        $show->field('is_sale', __('Is sale'));
        $show->field('order', __('Order'));
        $show->field('lock', __('Lock'));
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
        $form = new Form(new CardSell());

        $form->number('recording_id', __('Recording id'));
        $form->number('uid', __('Uid'));
        $form->number('card_id', __('Card id'));
        $form->decimal('price', __('Price'));
        $form->number('is_sale', __('Is sale'));
        $form->number('order', __('Order'));
        $form->switch('lock', __('Lock'));

        return $form;
    }
}
