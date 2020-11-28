@extends('layouts.app')


@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">
    <link rel="stylesheet" href="/block/css/index.css">
@endsection

@section('appcontent')
    <div class="page index">

        <div class="imgBox" onclick="window.location.reload()"><img src="/block/images/index-header.png?v=4" alt=""/>
        </div>

        <div class="page-bd">
            <div class="piglist">
                {{--产品列表开始--}}

                {{--            <div class="box" id="pig_level_1">--}}
                {{--                <div class="background-block">--}}
                {{--                </div>--}}
                {{--                <div class="pigimg">--}}
                {{--                    <img src="" alt="">--}}
                {{--                </div>--}}
                {{--                <div class="info fs22">--}}
                {{--                    <div>价值:<span>2000-3000</span></div>--}}
                {{--                    <div>领养时间:<span>12:00:00-12:30:00</span></div>--}}
                {{--                    <div>预约/即抢领养积分:<span>20.00/30.00</span>--}}
                {{--                    </div>--}}
                {{--                    <div>智能合约收益:<span>5天/30%</span></div>--}}
                {{--                    <div>可挖DTC:<span>10枚</span></div>--}}
                {{--                    <div>可挖成就点:<span>30点</span></div>--}}
                {{--                </div>--}}
                {{--                <div class="game_btn ">--}}
                {{--                    <div class='button   fs28 fw_b buttoning level_btn' onclick="">--}}
                {{--                        状态加载中--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <div class="name-box"><span class="goods-name fs34 fw_b"></span></div>--}}
                {{--                    <a class="rule-box" onclick="showRuleInfo(0)">--}}
                {{--                            <img src="/block/images/rule_info.png">--}}
                {{--                            <span class="text">详情</span>--}}
                {{--                    </a>--}}
                {{--            </div>--}}

                {{--产品列表结束--}}
            </div>

            <div class="rucian_tc" id="popupinfo" style="display: none">
                <div class="rucian_tctop">
                    <img id="popupinfoimg" src="" alt="" style="width: 70%;margin: 0 auto;">
                    <div class="rucian_ddlq"></div>
                    <a href="javascript:;" class="flex flex-center rucian_now" onclick="wozhidaol()">知道了</a>
                </div>
            </div>
            <style>
                .index .page-bd .piglist .box .pigimg img {
                    width: 1.9rem;
                    height: 1.9rem;
                }

                .rucian_tc {
                    width: 100%;
                    height: 100%;
                    position: fixed;
                    top: 0;
                    left: 0;
                    background-color: rgba(0, 0, 0, 0.48);
                    z-index: 1000;
                }

                .rucian_tctop {
                    /* background-color: rgb(255, 255, 255); */
                    width: 350px;
                    height: 350px;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    margin-left: -175px;
                    margin-top: -175px;
                    text-align: center;
                    border-radius: 7px;
                    perspective: 800px;
                }

                .rucian_ddlq {
                    color: rgb(255, 255, 255);
                    margin-top: 10px;
                    font-size: 17px;
                }

                .rucian_now {
                    height: 50px;
                    border-top: 1px solid rgba(0, 0, 0, 0.1);
                    /* color: rgb(0, 0, 0); */
                    color: rgb(255, 255, 255);
                }

                .index .page-bd .piglist .box .info div span {
                    color: #FFEB3B;
                    padding-left: 10px;
                }
            </style>

            <audio src="/block/audio/error.wav" id="mp3error"></audio>
            <audio src="/block/audio/success.wav" id="mp3successs"></audio>

        </div>


        <script>


            window.onload = function () {
                //列表加载
                renderAjax();
                //公告加载
                onloadannouncement();
            }

            function renderAjax() {
                $.ajax({
                    url: "{{route("block.home.doindex")}}",
                    type: "POST",
                    data: {"page": 1},
                    success: function (res) {
                        if (res.code) {
                            renderHtml(res.data.cardlist)
                        }
                    },
                    error: function () {
                        layerinfo("码农正在努力修复中");
                    }

                });
            }


            function buyproduct(obj, id) {
                if (obj.is(".fanzhi")) {
                    console.log("领养成功" + id)
                    //可以领养
                    $.ajax({
                        url: "/",
                        type: 'POST',
                        data: {"id": id},
                        dataType: 'json',
                        success: function (res) {
                            if (res.code) {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000)
                            }
                            layerinfo(res.msg)
                        }
                    });
                    return false;
                }
            }


            function renderHtml(datalist) {
                if (datalist.length > 0) {
                    var html = "";
                    $.each(datalist, function (index, item) {
                        html += '<div class="box" id="pig_level_' + item.id + '">'
                            + '<div class="background-block"></div>'
                            + '<div class="pigimg"> <img src="' + item.pic + '" alt="' + item.name + '"></div>'
                            + '<div class="info fs22">'
                            + item.htmlinfo
                            // + '  <div>价值:<span>' + item.min_worth_price + '-' + item.max_worth_price + '</span></div>'
                            // + '  <div>领养时间:<span>' + item.begin_time + '-' + item.end_time + '</span></div>'
                            // + '  <div>预约/即抢领养积分:<span>' + item.reserve_price + '/' + item.adopt_price + '</span></div>'
                            // + '  <div>智能合约收益:<span>' + item.profit_day + '天/' + item.profit_rate + '%</span></div>'
                            // + '  <div>等级:<span>' + item.levelname + '</span></div>'
                            // +'  <div>可挖DTC:<span>10枚</span></div>'
                            // +'  <div>可挖成就点:<span>30点</span></div>'
                            + '</div>'
                            + '<div class="game_btn" data-cardsellid="'+item.cardsell_id+'"> ' + btn_style(item.statusinfo) + '</div>'
                            + '<div class="name-box"><span class="goods-name fs34 fw_b">' + item.name + '</span></div>'
                            // + '<a class="rule-box" ><img src="/block/images/rule_info.png"><span class="text">详情</span></a>'
                            + '</div>'
                    });
                    $(".piglist").append(html);
                }
            }

            function btn_style(statusinfo) {
                var html = '<div class=""><i class="weui-loading"></i></div>';
                switch (statusinfo.status) {
                    case 1:
                        html = '<div class="button fs28 fw_b buttoned level_btn buttionClick" style="background: #0BB20C" data-type="1" data-index="' + statusinfo.index + '" >预约</div>';
                        break;
                    case 2:
                        var maxtime = statusinfo.begin_diff
                        //倒计时
                        timer = setInterval(function () {
                            if (maxtime > 0) {
                                jishi = Math.floor(maxtime / 60) + "分" + Math.floor(maxtime % 60) + "秒";
                                var html = '<div class="button fs28 fw_b buttoning level_btn" >' + jishi + '</div>';
                                $('#pig_level_' + statusinfo.index).find('div.game_btn').html(html);
                                --maxtime;
                            } else {
                                html = '<div class="button fs28 fw_b buttoning level_btn buttionClick" data-type="2" data-index="' + statusinfo.index + '">领养</div>'
                                $('#pig_level_' + statusinfo.index).find('div.game_btn').html(html);
                                clearInterval(timer);
                            }
                        }, 1000)
                        break;
                    case 3:
                        html = '<div class="button fs28 fw_b buttoning level_btn buttionClick"  style="background: red" data-type="2" data-index="' + statusinfo.index + '">领养</div>'
                        break;
                    case 4:
                        html = '<div class="button fs28 fw_b buttoning level_btn" style="background:linear-gradient(rgb(133, 101, 49) 0%, rgb(210, 148, 46) 100%)" data-index="' + statusinfo.index + '">繁殖中</div>';
                        break;
                    case 5:
                        html = '<div class="button fs28 fw_b buttoning level_btn" style="background: red" data-type="3" data-index="' + statusinfo.index + '">待领取</div>';
                        break;
                }
                return html
            }


            $(document).on("click", ".buttionClick", function (e) {
                var index = $(this).data('index');
                var type = $(this).data('type');
                var cardsell_id = $('#pig_level_' + index).find('div.game_btn').data('cardsellid')
                $.ajax({
                    url: "{{route("block.home.submit")}}",
                    type: 'POST',
                    data: {"index": index, "type": type,'cardsell_id':cardsell_id},
                    dataType: 'json',
                    success: function (res) {
                        if (res.code) {
                            //预约抢购启动定时器
                            if (type == 2) {
                                daojishijiazaihuoqujieguo(res.data.cardid);
                            } else {
                                window.location.reload();
                                $.alert(res.msg);
                            }
                        } else {
                            $.alert(res.msg);
                        }

                    },
                    error: function () {
                        layerinfo("码农正在努力修复中");
                    }
                });
            });


            var snapupresultTime = null

            function daojishijiazaihuoqujieguo(cardid) {

                var maxtime = 60
                // var maxtime = 10
                snapupresultTime = setInterval(function () {
                    if (maxtime > 0) {
                        jishi = Math.floor(maxtime / 60) + "分" + Math.floor(maxtime % 60) + "秒";
                        popupinfo()
                        --maxtime;
                    } else {
                        $.hideLoading();
                        snapupresult(cardid)
                        clearInterval(snapupresultTime);
                    }
                }, 1000)
            }


            function popupinfo(status) {
                $("#popupinfo").show()
                var html = "等待領養結果<br>請不要關閉";
                var src = "/block/images/home/lingyangzhong.png";
                if (status == 1) {
                    document.getElementById('mp3error').play();
                    var html = "没有领取到爱宠继续努力<br><br>";
                    var src = "/block/images/home/shibai.png";
                } else if (status == 2) {
                    document.getElementById('mp3successs').play();
                    var html = "";
                    var src = "/block/images/home/chenggong.png";
                }
                $("#popupinfoimg").attr("src", src)
                $(".rucian_ddlq").html(html)
            }

            var wozhidaot1 = null

            function wozhidaol() {
                clearInterval(snapupresultTime);
                $("#popupinfo").hide();
                setTimeout(function () {
                    window.location.reload();
                }, 3000);
                wozhidaot1 = window.setTimeout(function () {
                    window.location.reload();
                }, 3000)
            }

            window.clearTimeout(wozhidaot1);


            function snapupresult(cardid) {
                $.ajax({
                    url: "{{route("block.home.snapupresult")}}",
                    type: 'POST',
                    data: {"cardid": cardid},
                    dataType: 'json',
                    success: function (res) {
                        if (res.code) {
                            //抽中
                            popupinfo(2)
                        } else {
                            popupinfo(1)
                        }
                        clearInterval(snapupresultTime);
                    },
                    error: function () {
                        layerinfo("码农正在努力修复中");
                    }
                });
            }


            // setTimeout(function() {
            //     $.hideLoading();
            // }, 3000)


            // html =   '<div class="button fs28 fw_b buttoning level_btn fanzhi"  >繁殖中</div>';
            // html = '<div class="button fs28 fw_b buttoning level_btn">倒计时20s</div>'    ;
            // html = '<div class="button fs28 fw_b buttoning level_btn" >待领养</div>';
            // html = '<div class="button fs28 fw_b buttoning level_btn kaijiang" id="lingyang" >领养</div>'
            // html = '<div class="button fs28 fw_b buttoned level_btn" id="yuyue" >预约</div>';


            function onloadannouncement() {
                var _user_read_announcement = localStorage.getItem("_user_read_announcement");
                if (_user_read_announcement == null) {
                    //首页公告
                    $.ajax({
                        url: "{{route("block.home.announcement")}}",
                        data: {},
                        type: 'POST',
                        dataType: "json",
                        success: function (res) {
                            if (res.code && res.data != null) {
                                layer.open({
                                    content: res.data.content,
                                    title: res.data.title,
                                    skin: 'layer_bg'
                                    // ,btn: ['我知道了']
                                });
                            }
                        },
                        error: function () {
                            $.alert("公告系统正在维护中")
                        }
                    })
                }
            }
            // 阅读一次
            $(document).click(function(){
                localStorage.setItem("_user_read_announcement",1);
            });

        </script>


    </div>


    <style>
        .index {
            background-image: url('/block/images/background.png');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            overflow: auto;
            padding-bottom: 50px;
        }

        #fail .winning .red-head {
            background-image: url('/block/images/top1.png');
        }

        #fail .winning .red-body {
            background-image: url('/block/images/bottom2.png');
        }

        #fail .blin {
            background-image: url('/block/images/gold1.png');
        }
    </style>

@endsection

