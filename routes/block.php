<?php


use App\Middlewares\BlockAuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/login', "AuthController@login")->name("block.Auth.login");
Route::post('/dologin', "AuthController@dologin")->name("block.Auth.dologin");

Route::get('/register/{rcode?}', "AuthController@register")->name("block.Auth.register");
Route::post('/doregister', "AuthController@doregister")->name("block.Auth.doregister");

Route::match(['get', 'post'], '/pwd', "AuthController@pwd")->name("block.Auth.pwd");


Route::group(['prefix' => "file", "middleware" => BlockAuthMiddleware::class], function () {
    Route::post('/upload', "FileController@upload")->name("block.file.upload");
});


Route::group(['prefix' => "card", "middleware" => BlockAuthMiddleware::class], function () {
    Route::get('/index', "CardController@index")->name("block.card.index");
    Route::post('/worth', "CardController@worth")->name("block.card.worth");
});


//首页模块
Route::group(['prefix' => "home", "middleware" => BlockAuthMiddleware::class], function () {
    Route::get('/', "HomeController@index")->name("block.home.index");
    Route::post('/announcement', "HomeController@announcement")->name("block.home.announcement");
    Route::post('/doindex', "HomeController@doindex")->name("block.home.doindex");
    Route::post('/submit', "HomeController@submit")->name("block.home.submit");
    Route::post('/snapupresult', "HomeController@snapupresult")->name("block.home.snapupresult");
});


//游戏
Route::group(['prefix' => "game", "middleware" => BlockAuthMiddleware::class], function () {
    Route::get('/lottery', "GameController@lottery")->name("block.game.lottery");
    Route::post('/lotterypost', "GameController@lotterypost")->name("block.game.lotterypost");
    Route::post('/sign', "GameController@sign")->name("block.game.sign");
});

//资金操作
Route::group(['prefix' => "credit", "middleware" => BlockAuthMiddleware::class], function () {

    Route::get('/history', "CreditController@history")->name("block.credit.history");
    Route::match(['get', 'post'], '/transfer', "CreditController@transfer")->name("block.credit.transfer");
    Route::match(['get', 'post'], '/recharge', "CreditController@recharge")->name("block.credit.recharge");

    Route::match(['get', 'post'], '/sell', "CreditController@sell")->name("block.credit.sell");
});


//记录
Route::group(['prefix' => "record", "middleware" => BlockAuthMiddleware::class], function () {
    Route::get('/adopt', "RecordController@adopt")->name("block.record.adopt");
    Route::post('/adoptlist', "RecordController@adoptlist")->name("block.record.adoptlist");

    Route::get('/transfer', "RecordController@transfer")->name("block.record.transfer");
    Route::get('/yuyue', "RecordController@yuyue")->name("block.record.yuyue");

    Route::post('/confirmadoption', "RecordController@confirmadoption")->name("block.record.confirmadoption");
    Route::post('/sellrecording', "RecordController@sellRecording")->name("block.record.sellrecording");


    Route::get('/pricehistroy', "RecordController@pricehistroy")->name("block.record.pricehistroy");
});


//用户中心
Route::group(['prefix' => "user", "middleware" => BlockAuthMiddleware::class], function () {
    Route::get('/', "UserController@index")->name("block.user.index");


    Route::get('/team', "UserController@team")->name("block.user.team");
    Route::post('/doteam', "UserController@doteam")->name("block.user.doteam");


    Route::get('/collect', "UserController@collect")->name("block.user.collect");
    Route::post('/delcollect', "UserController@delcollect")->name("block.user.delcollect");
    Route::match(['get', 'post'], '/addcollect', "UserController@addcollect")->name("block.user.addcollect");


    Route::match(['get', "post"], '/passwd', "UserController@passwd")->name("block.user.passwd");

    Route::get('/setting', "UserController@setting")->name("block.user.setting");

    Route::get('/message', "UserController@message")->name("block.user.message");

    Route::match(['get', 'post'], '/verified', "UserController@verified")->name("block.user.verified");

    Route::post('/logout', "UserController@logout")->name("block.user.logout");

    Route::get('/qrcode', "UserController@qrcode")->name("block.user.qrcode");

});

//支付模块
Route::group(['prefix' => "payr", "middleware" => BlockAuthMiddleware::class], function () {
    Route::match(['get', 'post'], '/recording', "PayrController@recording")->name("block.payr.recording");
    Route::match(['get', 'post'], '/recordingdetail', "PayrController@recordingdetail")->name("block.payr.recordingdetail");
    Route::match(['get', 'post'], '/appeal', "PayrController@appeal")->name("block.payr.appeal");
});
