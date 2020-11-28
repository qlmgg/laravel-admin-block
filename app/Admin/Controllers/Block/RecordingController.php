<?php

namespace App\Admin\Controllers\Block;

use App\Models\Block\Recording;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Facades\DB;

class RecordingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\Block\Recording';

    public function index(Content $content)
    {
        $method = request()->input("method","grid");

        if ($method == "yuyue") {
            $title = "预约列表";
        }
        if ($method == "userhave") {
            $title = "会员的图鉴";
        }
        if ($method == "transfer") {
            $title = "待转让";
        }
        return $content
            ->title($title)
            ->description($this->description['index'] ?? trans('admin.list'))
            ->body($this->$method());
    }

    function transfer()
    {
        $grid = new Grid(new Recording());
        $grid->disableCreateButton();

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->equal('card_id','图鉴ID');
            $filter->like('user.phone', '手机号');
        });

        $where =[
            'winbidding' => 1,
            'userhave' => 1,
            'transfer_status'=>1,
        ];
        $grid->header(function ($query) use (&$where){
            $count  = $query->where($where)->count();
            return "<div style='padding: 10px;'>总共 ： $count</div>";
        });

        $grid->model()->where($where);

        $grid->actions(function ($actions) {
            // 去掉编辑
            $actions->disableEdit();
            // 去掉查看
            $actions->disableView();
        });

        $grid->model()->with(['card', 'releaseuser', 'user']);
        $grid->column('id', __('Id'))->sortable()->filter();
        $grid->column('user.phone', "持有人手机号")->filter();
        $grid->column('card_id', "图鉴ID")->filter();
        $grid->column('card.name', "图鉴名称")->filter();
        $grid->column('price', "价值");
        $grid->column('deduct_amount', '消耗积分')->totalRow(function ($amount) {
            return "<span class='text-danger text-bold'><i class='fa fa-yen'></i> {$amount} 元</span>";
        });
        return $grid;
    }

    function yuyue()
    {
        $grid = new Grid(new Recording());
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->equal('card_id','图鉴ID');
            $filter->like('user.phone', '手机号');
            $filter->between('created_at','预约时间')->datetime();
        });
        $grid->actions(function ($actions) {
            // 去掉编辑
            $actions->disableEdit();
            // 去掉查看
            $actions->disableView();
        });
        $where =[
            'type'=>1,
        ];
        $grid->model()->where($where);
        $grid->header(function ($query) use (&$where){
            $count = $query->where($where)->count();
            return "<div style='padding: 10px;'>总共 ： $count</div>";
        });
//        $grid->disableActions();
        $grid->model()->with(['card', 'releaseuser', 'user']);
        $grid->column('id', __('Id'))->sortable()->filter();
        $grid->column('user.phone', "预约人手机号")->filter();
        $grid->column('card.name', "图鉴名称")->filter();
        $grid->column('winbidding', "状态")->using([0 => "未抢到", 1 => "抢到"]);
        $grid->column('deduct_amount', '消耗积分')->totalRow(function ($amount) {
            return "<span class='text-danger text-bold'><i class='fa fa-yen'></i> {$amount} 元</span>";
        });
        $grid->column('created_at', __('预约时间'));
        return $grid;
    }

    function userhave()
    {
        $grid = new Grid(new Recording());
        $grid->disableCreateButton();

        $where =[
            'winbidding'=>1,
//            'userhave'=>1,
            'transfer_status'=>0,
        ];

        $grid->model()->where($where)->where('daydown',">" ,0);
        $grid->header(function ($query) use (&$where){
            $count = $query->where($where)->count();
            return "<div style='padding: 10px;'>总共 ： $count</div>";
        });
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->column(1/3, function ($filter) {
                $filter->equal('card_id','图鉴ID');
            });
            $filter->column(1/3, function ($filter) {
                $filter->like('user.phone', '手机号');
            });
            $filter->column(1/3, function ($filter) {
                $filter->between('userhave_at','拥有时间')->datetime();
            });
        });

        $grid->actions(function ($actions) {
            // 去掉编辑
            $actions->disableEdit();
            // 去掉查看
            $actions->disableView();
        });

        $grid->model()->with(['card', 'releaseuser', 'user']);
        $grid->column('id', __('Id'))->sortable()->filter();
        $grid->column('user.phone', "预约人手机号")->filter();
        $grid->column('card.name', "图鉴名称")->filter();
        $grid->column('price', "当前价值");

        $grid->column('userhave_at', __('拥有时间'));
        $grid->column('userhave', __('状态'))->using([
            0=>"等待支付",
            1=>'支付完成'
        ]);
        $grid->column('deduct_amount', '消耗积分')->totalRow(function ($amount) {
            return "<span class='text-danger text-bold'><i class='fa fa-yen'></i> {$amount} 元</span>";
        });

        return $grid;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Recording());

        $grid->column('id', __('Id'));
        $grid->column('card_id', __('Card id'));
        $grid->column('batch_id', __('Batch id'));
        $grid->column('ymd', __('Ymd'));
        $grid->column('card_sell_id', __('Card sell id'));
        $grid->column('release_uid', __('Release uid'));
        $grid->column('uid', __('Uid'));
        $grid->column('type', __('Type'));
        $grid->column('reserve', __('Reserve'));
        $grid->column('deduct_currency', __('Deduct credit'));
        $grid->column('deduct_amount', __('Deduct amount'));
        $grid->column('hashblock', __('Hashblock'));
        $grid->column('init_price', __('Init price'));
        $grid->column('price', __('Price'));
        $grid->column('winbidding', __('Winbidding'));
        $grid->column('winbidding_at', __('Winbidding at'));
        $grid->column('userhave', __('Userhave'));
        $grid->column('userhave_at', __('Userhave at'));
        $grid->column('expired', __('Expired'));
        $grid->column('transfer_status', __('Transfer status'));
        $grid->column('deleted_at', __('Deleted at'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Recording::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('card_id', __('Card id'));
        $show->field('batch_id', __('Batch id'));
        $show->field('ymd', __('Ymd'));
        $show->field('card_sell_id', __('Card sell id'));
        $show->field('release_uid', __('Release uid'));
        $show->field('uid', __('Uid'));
        $show->field('type', __('Type'));
        $show->field('reserve', __('Reserve'));
        $show->field('deduct_currency', __('Deduct credit'));
        $show->field('deduct_amount', __('Deduct amount'));
        $show->field('hashblock', __('Hashblock'));
        $show->field('init_price', __('Init price'));
        $show->field('price', __('Price'));
        $show->field('winbidding', __('Winbidding'));
        $show->field('winbidding_at', __('Winbidding at'));
        $show->field('userhave', __('Userhave'));
        $show->field('userhave_at', __('Userhave at'));
        $show->field('expired', __('Expired'));
        $show->field('transfer_status', __('Transfer status'));
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
        $form = new Form(new Recording());

        $form->number('card_id', __('Card id'));
        $form->number('batch_id', __('Batch id'));
        $form->number('ymd', __('Ymd'));
        $form->number('card_sell_id', __('Card sell id'));
        $form->number('release_uid', __('Release uid'));
        $form->number('uid', __('Uid'));
        $form->number('type', __('Type'));
        $form->number('reserve', __('Reserve'));
        $form->text('deduct_currency', __('Deduct credit'));
        $form->decimal('deduct_amount', __('Deduct amount'));
        $form->text('hashblock', __('Hashblock'));
        $form->decimal('init_price', __('Init price'));
        $form->decimal('price', __('Price'));
        $form->number('winbidding', __('Winbidding'));
        $form->datetime('winbidding_at', __('Winbidding at'))->default(date('Y-m-d H:i:s'));
        $form->number('userhave', __('Userhave'));
        $form->datetime('userhave_at', __('Userhave at'))->default(date('Y-m-d H:i:s'));
        $form->number('expired', __('Expired'));
        $form->number('transfer_status', __('Transfer status'));

        return $form;
    }
}
