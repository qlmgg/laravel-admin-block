<?php

namespace App\Admin\Controllers\Block;

use App\Admin\Actions\Block\CardAssignUser;
use App\Admin\Actions\Block\UserCardSell;
use App\Models\Block\Card;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Storage;

class CardController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '图鉴管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new Card());
        $grid->actions(function ($actions) {
            // 去掉查看
            $actions->disableView();
            $actions->add(new CardAssignUser());
            $actions->add(new UserCardSell());

        });
        $grid->column('id', __('Id'))->sortable()->filter();

        $grid->column('name', "名称");
        $grid->column('levelname', "级别名称");

        $grid->column('pic', "图片")->image(config("filesystems.disks.admin.url"), 80, 80);

        $grid->column('领养时间')->display(function () {
            return $this->begin_time . '~' . $this->end_time;
        });
        $grid->column('预约/即抢领养微分')->display(function () {
            return CreditNumberFormat($this->reserve_price) . '/' . CreditNumberFormat($this->adopt_price);
        });
        $grid->column('价值')->display(function () {
            return CreditNumberFormat($this->min_worth_price) . '-' . CreditNumberFormat($this->max_worth_price);
        });

        $grid->column('智能合约收益*天数')->display(function () {
            return $this->profit_rate . ' % * ' . $this->profit_day . "天";
        });
        $grid->column("created_at", '创建时间');
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
        $show = new Show(Card::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('levelname', __('Levelname'));
        $show->field('pic', __('Pic'));
        $show->field('begin_time', __('Begin time'));
        $show->field('end_time', __('End time'));
        $show->field('min_worth_price', __('Min worth price'));
        $show->field('max_worth_price', __('Max worth price'));
        $show->field('reserve_price', __('Reserve price'));
        $show->field('adopt_price', __('Adopt price'));
        $show->field('profit_rate', __('Profit rate'));
        $show->field('profit_day', __('Profit day'));
        $show->field('bestow1', __('Bestow1'));
        $show->field('bestow2', __('Bestow2'));
        $show->field('bestow3', __('Bestow3'));
        $show->field('bestow4', __('Bestow4'));
        $show->field('bestow5', __('Bestow5'));
        $show->field('bestow6', __('Bestow6'));
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
        $form = new Form(new Card());
        $path = "/card/" . date('Ymd');
        if (!Storage::disk('admin')->exists($path)) {
            Storage::makeDirectory($path);
        }

        $form->text('name', "名称")->required();
        $form->text('levelname', "级别名称");
        $form->time("begin_time", "领养开始时间")->format('HH:mm:ss')->default(date('H:i:s'));
        $form->time("end_time", "领养结束时间")->format('HH:mm:ss')->default(date('H:i:s', time() + 30 * 60));
        $form->image('pic', "头图")->move($path)->removable()->uniqueName()->required();
        $form->fieldset('常规设置', function (Form $form) {
            $form->decimal('reserve_price', "预约微分")->required();
            $form->decimal('adopt_price', "领养微分")->required();
            $form->decimal('min_worth_price', "最小价值")->required();
            $form->decimal('max_worth_price', "最大价值")->required();
        });

        $form->fieldset('智能合约设置', function (Form $form) {
            $form->number('profit_day', "合约天数")->default(1);
            $form->rate("profit_rate", "合约比例")->default(1);
            $form->html("<span style='color: red'>合约计算方式：合约天数 * 合约比例</span>");
        });

        $form->fieldset('收益比例设置', function (Form $form) {
            $bestows = CardBestow2credit();
            foreach ($bestows as $bestow =>$credit){
                $form->number($bestow, __( "产生".CreditChinese($credit)."数量" ))->default(0);
            }
//            $form->html("<span style='color: red'>计算方式：（购买价格 * 合约比例  / 100 / 合约天数）/ 比例 </span>");
        });

        return $form;
    }
}
