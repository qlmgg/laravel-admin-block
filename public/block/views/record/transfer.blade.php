@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">
    <link rel="stylesheet" href="/block/css/custom.css">
    <link rel="stylesheet" href="/block/css/transfer.css">
    <script src="/block/js/fastclick.js"></script>
    <style>
        .logs {
            /*background-image: url(/block/images/background.png);*/
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
                <a href="{{route("block.record.adopt")}}">
                    <img src="/block/images/lingyang.png">
                    <span>领养记录</span>
                </a>
                <a class="active" href="{{route("block.record.transfer")}}">
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
                    <a class="weui-navbar__item color_9 weui-bar__item--on" href="#tab1">待转让</a>
                    <a class="weui-navbar__item color_9" href="#tab2"> 转让中/拒绝 </a>
                    <a class="weui-navbar__item color_9" href="#tab3">已完成 </a>
                    <a class="weui-navbar__item color_9" href="#tab4">申诉</a>
                </div>

                <div class="weui-tab__bd">
                    <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">

                        @if($waitlist)
                            @foreach($waitlist as $v)
                                <div class="Box">
                                    <div class="img-box"><img src="{{$v['cardpic']}}"></div>
                                    <div class="info-box">
                                        <div class="content fs26 color_3">
                                            <div class="txtTitle">{{$v['cardname']}}</div>
                                            <div><span class="fw_left">价值：</span><span
                                                    class="color_r fw_b">{{$v['jiazhi']}}</span>
                                            </div>
                                            <div><span class="fw_left">智能合约收益：</span><span
                                                    class="color_r fw_b">{{$v['heyue']}}</span></div>
                                            <div><span class="fw_left">获得收益：</span><span
                                                    class="color_r fw_b">{{$v['shouyi']}}</span></div>
                                            <div><span class="fw_left">领养时间：</span>{{$v['userhave_at']}}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="more fs24 color_9"><span>暂无数据</span></div>
                        @endif
                    </div>
                    <div id="tab2" class="weui-tab__bd-item">
                        @if($inlist)
                            @foreach($inlist as $v)
                                <div class="Box"><a href="{{route("block.payr.recordingdetail",['id'=>$v['id']])}}" >
                                        <div class="titie fs26 color_3 bor_b"><span
                                                class="fw_b">区块编号:{{$v['hashblock']}}</span>
                                        </div>
                                        <div class="content fs26 color_3">
                                            <div>宠物图鉴:{{$v['cardname']}}</div>
                                            <div>价值：<span class="color_r fw_b">{{$v['price']}}</span></div>
                                            <div>智能合约收益：<span class="color_r fw_b">{{$v['heyue']}}</span></div>
                                            <div>领养方：{{$v['zhuanrangfang']}}</div>
                                            <div>转让时间：{{$v['created_at']}}</div>
                                        </div>
                                    </a>
                                    <div class="button">
                                        <a href="javascript:void(0)"
                                           data-url="{{route("block.payr.recordingdetail",['id'=>$v['id']])}}"
                                           id="querentiaozhuan">
                                            <div class="fs30 fw_b color_r">确认</div>
                                        </a>
                                        <a href="{{route("block.payr.appeal",['id'=>$v['id']])}}"
                                           class="right fs30 fw_b color_r">申诉</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="more fs24 color_9"><span>暂无数据</span></div>
                        @endif

                    </div>
                    <script>
                        $(document).on("click", "#querentiaozhuan", function () {
                            var url = $(this).attr("data-url");
                            $.modal({
                                title: "",
                                text: "支付完成之后将在转让中消失，进入已完成中",
                                buttons: [
                                    {
                                        text: "确认", onClick: function () {
                                            if (url) {
                                                self.location.href = url
                                            }
                                        }
                                    },
                                    {text: "取消", className: "default"},
                                ]
                            });
                        });


                    </script>

                    <div id="tab3" class="weui-tab__bd-item">
                        @if($overlist)
                            @foreach($overlist as $v)
                                <a href="{{route("block.payr.recordingdetail",['id'=>$v['id']])}}">
                                    <div class="Box">
                                        <div class="titie fs26 color_3 bor_b"><span
                                                class="fw_b">区块编号:{{$v['hashblock']}}</span>
                                        </div>
                                        <div class="content fs26 color_3">
                                            <div>宠物图鉴:{{$v['cardname']}}</div>
                                            <div>价值：<span class="color_r fw_b">{{$v['jiazhi']}}</span></div>
                                            <div>智能合约收益：<span class="color_r fw_b">{{$v['heyue']}}</span></div>
                                            <div>转让时间：{{$v['userhave_at']}}</div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="more fs24 color_9"><span>暂无数据</span></div>
                        @endif
                    </div>
                    <div id="tab4" class="weui-tab__bd-item">
                        @if($shensulist)
                            @foreach($shensulist as $v)
                            <a href="{{route("block.payr.recordingdetail",['id'=>$v['recording_id']])}}" >
                                <div class="Box">
                                    <div class="titie fs26 color_3 bor_b"><span class="fw_b">申述理由</span><span class="color_9"></span></div>
                                    <div class="content fs26 color_3">
                                        <div class="reason">{{$v['reason']}}</div>
                                        <div class="top fs24 color_9">区块编号:{{$v['recordinghashblock']}}</div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        @else
                            <div class="more fs24 color_9"><span>暂无数据</span></div>
                        @endif


                    </div>
                </div>


            </div>

        </div>


    </div>
@endsection
