<?php

namespace App\Admin\Controllers;


use App\Admin\Actions\CurrencyForm;
use App\Models\User;
use App\Models\UserTree;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;


class UserTreeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户树';

    protected function grid()
    {
        $gridtree  =  UserTree::tree(function ($tree){
            $tree->disableSave();
            $tree->disableSave();
            $tree->disableCreate();
            return $tree;
        });
        return $gridtree;
    }

}
