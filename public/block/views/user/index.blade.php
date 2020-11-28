@extends('layouts.app')


@section('header')
    <link rel="stylesheet" href="/block/css/zpui2.css">
    <link rel="stylesheet" href="/block/css/all2.css">
    <link rel="stylesheet" href="/block/css/personalcenter.css">
@endsection


@section('appcontent')
    <div class="page my">
        <div class="page-bd">
            <div class="info">

                <div class="name">
                    <div class="avatar-box">
                        <img src="/block/images/touxiang.png" alt="">
                    </div>
                    <div class="left">
                        <div class="fw_b fs32 color_3 nickname">
                            <span class="username">{{$info['user']['name']}}</span>
                            <span class="fs24 color_6 level_name">正式玩家</span>
                        </div>
{{--                        <span class="fs24 color_6 mobile"style="color: #00C4EE">邀请码：<i>{{$info['user']['id']}}</i></span>--}}
                        <span class="fs24 color_6 mobile">手机号：<i>{{$info['user']['phone']}}</i></span>

                    </div>
                    <a href="{{route("block.user.setting")}}" class="setting"><img src="/block/images/setIcon.png"
                                                                                   alt=""></a>
                </div>

                <div class="data">
                    @if( $info['shenfen'] == null)
                        <div class="row border_b">
                            <div class="list border_r">

                                <a href="{{route("block.user.addcollect",['source'=>'verified'])}}">
                                    <div class="fs36 fw_b color_3 pig pig_currency">点击进行实名认证</div>
                                </a>

                            </div>
                        </div>
                    @else
                        <div class="row border_b">
                            <div class="list border_r">
                                <div class="fs36 fw_b color_3 pig pig_currency">USDT</div>
                                <span class="fs24 color_3 "><a class="color_3"
                                                               href="{{$info['usdt']['href']}}">{{$info['usdt']['value']}}</a></span>
                            </div>
                            <div class="list border_r">
                                <div class="fs36 fw_b color_3 pig pig_currency">积分</div>
                                <span class="fs24 color_3 "><a class="color_3"
                                                               href="{{$info['jifen']['href']}}">{{$info['jifen']['value']}}</a></span>
                            </div>
                            <div class="list border_r">
                                <div class="fs36 fw_b color_3 pig pig_currency">DTC</div>
                                <span class="fs24 color_3 "><a class="color_3"
                                                               href="{{$info['dtc']['href']}}">{{$info['dtc']['value']}}</a></span>
                            </div>


                        </div>

                        <div class="row">
                            <div class="list border_r">
                                <div class="fs36 fw_b color_3 contract_revenue">累计收益</div>
                                <span class="fs24 color_3"><a class="color_3"
                                                              href="{{$info['leiji']['href']}}">{{$info['leiji']['value']}}</a></span>
                            </div>
                            <div class="list border_r">
                                <div class="fs36 fw_b color_3 pig_price">总资产</div>
                                <span class="fs24 color_3"><a href="{{$info['assets']['href']}}"
                                                              class="color_3">{{$info['assets']['value']}}</a></span>
                            </div>
                            <div class="list">
                                <div class="fs36 fw_b color_3 extension_income">推荐收益</div>
                                <span class="fs24 color_3"><a class="color_3"
                                                              href="{{$info['share']['href']}}">{{$info['share']['value']}}</a></span>
                            </div>
                        </div>
                    @endif
                </div>


                <div class="logBox">
                    <div class="info-group scroll">
                        <div class="notice-text">网站公告：这是公告内容，这是公告内容，这是公告内容，这是公告内容</div>
                    </div>
                </div>


                <div class="gird">
                    <div class="row border_b">
                        @if($info['qiandao'])
                            <a href="javascript:void(0)">
                                <img src="/block/images/tubiao/checkin.png" alt="">
                                <span class="fs24 color_3">已签到</span>
                            </a>
                        @else
                            <a href="javascript:void(0)" onclick="qiandao()">
                                <img src="/block/images/tubiao/checkin.png" alt="">
                                <span class="fs24 color_3">签到</span>
                            </a>
                        @endif


                        <a href="{{route("block.user.team")}}" class="border_r">
                            <img src="/block/images/tubiao/myIcon07.png" alt="">
                            <span class="fs24 color_3">我的团队</span>
                        </a>
                        <a href="{{route("block.user.qrcode")}}">
                            <img src="/block/images/tubiao/myIcon08.png" alt="">
                            <span class="fs24 color_3">邀请好友</span>
                        </a>

                    </div>
                    <div class="row">
                        <a href="{{route("block.user.passwd")}}">
                            <img src="/block/images/tubiao/myIcon04.png" alt="">
                            <span class="fs24 color_3">安全中心</span>
                        </a>


                        @if( $info['shenfen'] == null)
                            <a href="{{route("block.user.addcollect",['source'=>'verified'])}}">
                                <img src="/block/images/tubiao/myIcon05.png" alt="">
                                <span class="fs24 color_3 authentication">实名认证</span>
                            </a>
                        @else
                            <a href="javascript:void(0)" onclick="$.alert('已认证')">
                                <img src="/block/images/tubiao/myIcon05.png" alt="">
                                <span class="fs24 color_3 authentication">实名认证</span>
                            </a>
                        @endif


                        <a href="{{route("block.user.collect")}}">
                            <img src="/block/images/tubiao/myIcon06.png" alt="">
                            <span class="fs24 color_3">收款方式</span>
                        </a>
                    </div>
                    <div class="row">
                        <a href="javascript:void(0)" onclick="kaifazhong()">
                            {{--                        <a href="http://yt.fa1577.cn/kf.html" class="">--}}
                            <img src="/block/images/tubiao/service.png" alt="">
                            <span class="fs24 color_3">客服中心</span>
                        </a>

                        <a href="javascript:void(0)" onclick="kaifazhong()">
                            <img src="/block/images/tubiao/dtc.png" alt="">
                            <span class="fs24 color_3">DTC交易所</span>
                        </a>

                        <a href="{{route("block.user.message")}}">
                            <img src="/block/images/tubiao/myIcon09.png" alt="">
                            <span class="fs24 color_3">系统消息</span>
                        </a>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script>
        function qiandao() {
            $.ajax({
                url: "{{route("block.game.sign")}}",
                data: {},
                type: 'POST',
                success: function (res) {
                    if (res.code) {
                        setTimeout(function () {
                            window.location.reload()
                        }, 1000);
                        $.toptip(res.msg, 2000, 'success');
                    } else {
                        $.toptip(res.msg, 2000, 'warning');
                    }
                },
                error: function () {
                    console.log('error');
                }
            });
        }
    </script>

    <style>

        .my .page-bd .gird {
            margin: 0 0rem 0rem;
        }

        .avatar-box {
            margin-top: 0px;
        }

        .bottom-tabbar a .icon {
            width: 0.4rem;
            height: 0.45rem;
            display: inline-block;
            margin-bottom: 0;
        }
    </style>

@endsection
