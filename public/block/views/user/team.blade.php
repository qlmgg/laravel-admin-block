@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">
    <link rel="stylesheet" href="/block/css/team.css">
    <style>
        .tlCard {
            background-color: rgba(197, 80, 28, 0.65);
        }

        .txtTime, .txtName {
            color: #FFF;
        }

        .tl_left p:nth-child(2), .tl_left p, .tl_middle p.txtIdent {
            color: #FEE900;
        }

        .inivteFriend .top p {
            color: #FEE900;
            font-size: 0.2rem;
            font-weight: bold;
            border: none;
        }
    </style>
@endsection

@section('appcontent')
    <div class="page inivteFriend">
        {{--header--}}
        <div class="page-hd">
            <div class="header bor-1px-b">
                <div class="header-left">
                    <a href="javascript:history.go(-1)" class="left-arrow"></a>
                </div>
                <div class="header-title">我的团队</div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>


        <div class="top " style="display:flex;text-align: center;height:auto;padding:0.15rem 2%;flex-wrap: wrap;">

            <div style="width:32%;">
                <p>一代人数</p>
                <p style="margin-left: 0;" id="onecount">N/A</p>
            </div>
            <div style="width:32%">
                <p>二代人数</p>
                <p style="margin-left: 0;" id="twocount">N/A</p>

            </div>
            <div style="width:32%">
                <p>三代人数</p>
                <p style="margin-left: 0;" id="threecount">N/A</p>
            </div>

            {{--            <p style="margin-top: 0.15rem;color: #FFF;font-size: 0.25rem;">无限代人数：1人</p>--}}
        </div>


        <div class="bottom">
            <div id="list" class='demos-content-padded'></div>

            {{--                        <div class="layui-collapse">--}}
            {{--                            <div class="layui-colla-item">--}}
            {{--                                <div class="tlCard layui-colla-title">--}}
            {{--                                    <div class="tl_left"><p class="txtName">用户名</p>--}}
            {{--                                        <p class="txtPhone">手机号</p>--}}
            {{--                                    </div>--}}
            {{--                                    <div class="tl_middle">--}}
            {{--                                        <p class="txtIdent">2020-02-18</p>--}}
            {{--                                        <p class="isauth">会员等级：aaaa</p>--}}
            {{--                                        <p class="txtTime">第1代</p></div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}


            <div class="weui-loadmore loadLink" style="display: block;">
                <span class="weui-loadmore__tips">——我也是有底线的——</span>
            </div>

            <div class="pull-loading" id="pull-loading"></div>
        </div>

        <script type="text/javascript">
            var pages = 1;
            var pagelock = 0;
            $(document).ready(function () {
                $(".bottom").scroll(function () {
                    var nScrollHight = 0;
                    var nScrollTop = 0;
                    var nDivHight = $(".bottom").height();
                    nScrollHight = $(this)[0].scrollHeight;
                    nScrollTop = $(this)[0].scrollTop;
                    if (nScrollTop + nDivHight >= nScrollHight) {
                        console.info(pagelock);
                        $(".loadLink").hide();
                        $(".loadmore").show();
                        setTimeout(function () {
                            rendAjax(pages);
                        }, 10)
                        if (pagelock == 0) {
                            pages++;
                        }
                    }
                });
            });

            function rendAjax(pages) {
                $.ajax({
                    url: "{{route("block.user.doteam")}}",
                    type: 'post',
                    data: {"page": pages},
                    dataType: 'json',
                    success: function (res) {
                        console.log(res);
                        if (res.code) {
                            userlist(res.data.userlist.data);
                            rendercountryroot(res.data.countryroot);
                        } else {
                            pagelock = 1;
                        }
                        loading = true;
                    },
                    error: function () {
                        console.log('error');
                    },
                })

            }

            function userlist(userlist) {
                var str = '';
                $.each(userlist, function (i, obj) {
                    str += '<div class="layui-collapse"><div class="layui-colla-item">'
                        + '<div class="tlCard layui-colla-title">'
                        + '<div class="tl_left">'
                        + '<p class="txtName">' + obj.name + '</p>'
                        + '<p class="txtPhone">' + obj.phone + '</p></div>'
                        + '<div class="tl_middle">'
                        + '<p class="txtIdent">' + obj.created_at + '</p>'
                        + '<p class="isauth">会员等级：aaaa</p>'
                        + '<p class="txtTime">第:' + obj.root + '代</p></div></div></div></div>';
                });
                $("#list").append(str)
            }

            window.onload = function () {
                rendAjax();
            };


            function rendercountryroot(countryroot) {
                if (typeof (countryroot[1]) !== "undefined") {
                    $("#onecount").html(countryroot[1])
                } else {
                    $("#onecount").html("0")
                }

                if (typeof (countryroot[2]) !== "undefined") {
                    $("#twocount").html(countryroot[2])
                } else {
                    $("#twocount").html("0")
                }

                if (typeof (countryroot[3]) !== "undefined") {
                    $("#threecount").html(countryroot[3])
                } else {
                    $("#threecount").html("0")
                }
            }
        </script>

    </div>

@endsection
