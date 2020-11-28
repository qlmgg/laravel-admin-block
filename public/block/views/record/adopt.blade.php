@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">
    <link rel="stylesheet" href="/block/css/custom.css">
    <link rel="stylesheet" href="/block/css/adopt.css">
    <script src="/block/js/fastclick.js"></script>
    <style>
        .logs {
            background-image: url(/block/images/background.png);
            background-size: 100% auto;
        }
    </style>
    <script>
        $(function () {
            FastClick.attach(document.body);
        });
    </script>
@endsection

@section('appcontent')




    <div class="page logs">

        <div class="page-hd">
            <div class="header">
                <img src="/block/images/my-header.png" class="headerImg">
            </div>
            <div class="logs-nav">
                <a class="active" href="{{route("block.record.adopt")}}">
                    <img src="/block/images/lingyang.png">
                    <span>领养记录</span>
                </a>
                <a href="{{route("block.record.transfer")}}">
                    <img src="/block/images/zhuanrang.png">
                    <span>转让记录</span>
                </a>
                <a href="{{route("block.record.yuyue")}}">
                    <img src="/block/images/yuyue.png">
                    <span>预约记录</span>
                </a>
            </div>
        </div>


        <div class="page-bd">
            <div class="weui-tab">

                <div class="weui-navbar">
                    <a class="weui-navbar__item color_9 weui-bar__item--on" href="#tab1">领养中
                    </a>
                    <a class="weui-navbar__item color_9" href="#tab2"> 已领养 </a>
                    <a class="weui-navbar__item color_9" href="#tab3"> 取消/申诉 </a>
                    <!--<a class="weui-navbar__item color_9" href="#tab4">已销毁</a>-->
                </div>

                <div class="weui-tab__bd">
                    <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">
                        <div id="adoptinglist"></div>
                        <div class="more fs24 color_9"><span>暂无数据</span></div>
                    </div>
                    <div id="tab2" class="weui-tab__bd-item">
                        <div id="adoptedlist"></div>
                        <div class="more fs24 color_9"><span>暂无数据</span></div>
                    </div>
                    <div id="tab3" class="weui-tab__bd-item">
                        <div id="shensulist"></div>


                        <div class="more fs24 color_9"><span>暂无数据</span></div>
                    </div>
                    <div id="tab4" class="weui-tab__bd-item">
                        <div class="more fs24 color_9"><span>暂无数据</span></div>
                    </div>
                </div>


            </div>

        </div>

        <style>
            .logs .Box {
                margin: 0.133333rem 0.133333rem 0 0.133333rem;
                background-color: rgba(197, 80, 28, 0.85);
                border-radius: 0.066667rem;
                overflow: hidden;
            }

            .Box {
                margin: .2rem;
                background: rgba(197, 80, 28, 0.65);
            }

            .logs .Box .titie {
                margin-left: 0.133333rem;
                height: 0.666667rem;
                padding-left: 0.066667rem;
                display: -webkit-box;
                display: -webkit-flex;
                display: flex;
                -webkit-box-pack: justify;
                -webkit-justify-content: space-between;
                justify-content: space-between;
                -webkit-box-align: center;
                -webkit-align-items: center;
                align-items: center;
                padding-right: 0.2rem;
            }

            .logs .Box .content .reason {
                margin: 0.066667rem 0 0.2rem;
            }

            .logs .top {
                display: -webkit-box;
                display: -webkit-flex;
                display: flex;
                -webkit-box-pack: end;
                -webkit-justify-content: flex-end;
                justify-content: flex-end;
            }

            .logs .Box .content div {
                margin-bottom: 0.026667rem;
            }
        </style>

        <script>


            window.onload = function () {
                rendAjax();
            };

            function rendAjax() {
                $.ajax({
                    url: "{{route("block.record.adoptlist")}}",
                    type: "POST",
                    success: function (res) {
                        if (res.code) {
                            // adoptedlist(res.data.adoptedlist)
                            adoptedlist(res.data.adoptedlist);
                            adoptinglist(res.data.adoptinglist);
                            shensulist(res.data.shensulist);
                        }
                    }
                })
            }



            function shensulist(listinfo) {
                var html = "";
                $.each(listinfo, function (i, obj) {
                    html += '<a><div class="Box"><div class="titie fs26 color_3 bor_b">'
                    +'<span class="fw_b">申述理由</span><span class="color_9"></span></div>'
                    +'<div class="content fs26 color_3">'
                    +' <div class="reason">'+obj.reason+'</div>'
                    +' <div class="top fs24 color_9">区块编号:'+obj.recordinghashblock+'</div>'
                    +'</div> </div> </a>'
                });
                $("#shensulist").append(html);
            }

            function adoptinglist(listinfo) {
                var html = '';
                $.each(listinfo, function (i, obj) {
                    html += '<a><div class="Box">'
                        + '<div class="titie fs26 color_3 bor_b">'
                        + '<span class="fw_b">区块编号:' + obj.hashblock + '</span><span class="color_r">区块写入中</span>'
                        + '</div>'
                        + '<div class="content fs26 color_3 blcokcontent_' + obj.id + '">'
                        + '<div class="contentDiv"><span class="txtTitle">宠物图鉴:</span>' + obj.blockname + '</div>'
                        + '<div class="contentDiv"><span class="txtTitle">价值：</span><span class="color_r fw_b">' + obj.blockjiazhi + '</span></div>'
                        + '<div class="contentDiv"><span class="txtTitle">智能合约收益：</span><span class="color_r fw_b">' + obj.blockheyue + '</span></div>'
                        + '<div class="contentDiv"><span class="txtTitle">首日收益：</span><span class="color_r fw_b">'+obj.nowshouyi+'</span></div>'
                        + '<div class="contentDiv"><span class="txtTitle">领养时间：</span>' + obj.created_at + '</div>'
                        + '<div class="confirm_time">' + daojishi(obj.daojishi, obj.id) + '</div>'
                        + '<div class="button daojishiqueren" data-id="' + obj.id + '"><div class="fs30 fw_b color_r">'+obj.buttion+'</div></div>'
                        + ' </div></div></a>'
                });
                $("#adoptinglist").append(html);
            }

            function daojishi(maxtime, indexid) {
                setInterval(function () {
                    if (maxtime > 0) {
                        minutes = Math.floor(maxtime / 60);
                        seconds = Math.floor(maxtime % 60);
                        jishi = minutes + "分" + seconds + "秒";
                        var html = '<span class="txtTitle">确认剩余时间：</span><span class="color_r fw_b remaining_time" >' + jishi + '</span>'
                        $(".blcokcontent_" + indexid).find(".confirm_time").html(html)
                        --maxtime;
                    } else {
                        $(".blcokcontent_" + indexid).find(".daojishiqueren").hide()
                        $(".blcokcontent_" + indexid).find(".confirm_time").html("")
                    }
                }, 1000)
                return "";
            }

            $(document).on("click", ".daojishiqueren", function (e) {
                var id = $(this).attr("data-id")
                $.confirm({
                    title: '确认领取',
                    text: '支付完成后，将在领养中消失进入已领养中',
                    onOK: function () {
                        //点击确认
                        $.ajax({
                            url: "{{route("block.record.confirmadoption")}}",
                            type: "POST",
                            data: {"id": id, 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (res) {
                                if (res.code) {
                                    setTimeout(function () {
                                        if (res.url) {
                                            self.location.href = res.url
                                        } else {
                                            location.reload()
                                        }
                                    }, 800)
                                }
                                $.showLoading(res.msg);
                                setTimeout(function () {
                                    $.hideLoading();
                                }, 3000)
                            },
                            error: function () {
                                console.log('error');
                            }
                        })

                    },
                    onCancel: function () {
                    }
                });
            });


            function adoptedlist(listinfo) {
                console.log(typeof listinfo)
                console.log(listinfo.toString().length)
                var html = '';
                $.each(listinfo, function (i, obj) {
                    html += '<div class="Box">'
                        + '<div class="titie fs26 color_3 bor_b"><span class="fw_b">区块编号:' + obj.hashblock + '</span></div>'
                        + '<div class="content fs26 color_3">'
                        + '<div class="contentDiv"><span class="txtTitle">宠物图鉴:</span>' + obj.blockname + '</div>'
                        + '<div class="contentDiv"><span class="txtTitle">价值：</span><span class="color_r fw_b">' + obj.blockjiazhi + '</span></div>'
                        + '<div class="contentDiv"><span class="txtTitle">智能合约收益：</span><span class="color_r fw_b">' + obj.blockheyue + '</span></div>'
                        + '<div class="contentDiv"><span class="txtTitle">每日收益：</span><span class="color_r fw_b">' + obj.nowshouyi + '</span></div>'
                        + '<div class="contentDiv"><span class="txtTitle">出售总价：</span><span class="color_r fw_b">' + obj.chushoujiage + '</span></div>'
                        + '<div class="contentDiv"><span class="txtTitle">领养时间：</span>' + obj.created_at + '</div>'
                        // + '<div class="contentDiv"><span class="txtTitle">转存收益：</span><span class="color_r fw_b">' + obj.zhuancun + '</span></div>'
                    if (obj.buttion) {
                        html += '<div class="button"><div class="fs30 fw_b color_r" data-id="' + obj.id + '" data-buttion="' + obj.buttion + '">收益中</div></div>'
                    } else {
                        html += '<div class="button"><div class="fs30 fw_b color_r  tiqianmaichu" data-id="' + obj.id + '">提前卖出</div></div>'
                    }

                    html += '</div></div>'
                });
                $("#adoptedlist").append(html);
            }


            $(document).on("click", '.tiqianmaichu', function () {
                var id = $(this).attr("data-id")
                $.confirm({
                    title: '确定提前卖出',
                    text: '',
                    onOK: function () {
                        //点击确认
                        $.ajax({
                            url: "{{route("block.record.sellrecording")}}",
                            type: "POST",
                            data: {"id": id},
                            success: function (res) {
                                if (res.code) {
                                    setTimeout(function () {
                                        if (res.url) {
                                            self.location.href = res.url
                                        } else {
                                            location.reload()
                                        }
                                    }, 2000)
                                }
                                $.alert(res.msg);
                            },
                            error: function () {
                                console.log('error');
                            }
                        })
                    },
                    onCancel: function () {
                    }
                });


            })
        </script>


    </div>
@endsection
