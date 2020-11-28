<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('user', UserContrlloer::class);
    $router->resource('usertree', UserTreeController::class);

    $router->resource('identity', IdentityController::class);
    $router->resource('creditlog', CreditLogController::class);
    $router->resource('creditreview', CreditReviewController::class);
    $router->resource('config', ConfigController::class);

    // php artisan admin:make Block\RecordingAppealController --model=App\Models\Block\RecordingAppeal
    $router->group(['prefix'=>"block","namespace"=>"\App\Admin\Controllers\Block"],function ($router){
        $router->resource('card', CardController::class);
        $router->resource('batche', BatcheController::class);
        $router->resource('notice', NoticeController::class);
        $router->resource('recording', RecordingController::class);
        $router->resource('sell', CardSellController::class);
        $router->resource('appeal', RecordingAppealController::class);
        $router->resource('config', ConfigController::class);
    });

});
