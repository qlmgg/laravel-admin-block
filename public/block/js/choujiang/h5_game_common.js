var $maskRule = $("#mask-rule"),//规则遮罩层
    $mask = $("#mask"),//红包遮罩层
    $winning = $(".winning"),//红包
    $card = $("#card"),
    $close = $("#close"),
    prizes={};
    //link = false;//判断是否在链接跳转中

//规则
$(".rule").click(function () {
    $maskRule.show();
});
$("#close-rule").click(function () {
    $maskRule.hide();
});

/*中奖信息提示*/
function win(data) {

    console.log("抢到：",data)
    const prize = data.data;
    layer.open({
        content: "恭喜您抢到 "+prize.name
        ,skin: 'msg'
        ,time: 2 //2秒后自动关闭
    });


    // $(".winImg").attr({"src":prize.images});
    // $mask.find('.win').text(data.msg);
    // $mask.find('.prize-name').text('获得 '+prize.name+' ！');
    // //遮罩层显示
    // $mask.show();
    // $winning.addClass("reback");
    // setTimeout(function () {
    //     $card.addClass("pull");
    // }, 500);
    //
    // //关闭弹出层
    // $("#close,.win,.btn").click(function () {
    // //$close.click(function () {
    //     $mask.hide();
    //     $winning.removeClass("reback");
    //     $card.removeClass("pull");
    // });
    /*$(".win,.btn").click(function () {
        link = true;
    });*/
}
function fail(data) {
    console.log("没有抢到：",data)
    const prize = data.data;
    $(".winImg").attr({"src":'/uploads/images/'+prize.images});
    $mask.find('.win').text(data.message);
    // $mask.find('.prize-name').text('赠送 '+prize.name+' ！');
    $mask.addClass("fail");
    //遮罩层显示
    $mask.show();
    $winning.addClass("reback");
    setTimeout(function () {
        $card.addClass("pull");
    }, 500);

    //关闭弹出层
    $("#close,.win,.btn").click(function () {
        //$close.click(function () {
        $mask.hide();
        $winning.removeClass("reback");
        $card.removeClass("pull");
    });
    /*$(".win,.btn").click(function () {
        link = true;
    });*/
}

//此处可以在commonjs中合并
function queryString(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.search);
    if(results === null) {
        return "";
    }
    else {
        return decodeURIComponent(results[1].replace(/\+/g, " "));
    }
}



