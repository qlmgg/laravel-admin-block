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
                <a href="{{route("block.record.adopt")}}">
                    <img src="/block/images/lingyang.png">
                    <span>领养记录</span>
                </a>
                <a href="{{route("block.record.transfer")}}">
                    <img src="/block/images/zhuanrang.png">
                    <span>转让记录</span>
                </a>
                <a class="active" href="{{route("block.record.yuyue")}}">
                    <img src="/block/images/yuyue.png">
                    <span>预约记录</span>
                </a>
            </div>
        </div>


        <div class="page-bd">

            <div class="weui-cells">

                @foreach($recording as $v)
                <div class="weui-cell box">
                    <div class="weui-cell__bd">
                        <div class="fs28 color_3 fw_b top">{{$v['cardname']}}<span class="fs24 color_9">({{$v['statusinfo']}})</span></div>
                        <div class="fs24 color_9">{{$v['created_at']}}</div>
                    </div>
                    <div class="weui-cell__ft">
                        <div class="fs30 color_3 fw_b top">12</div>
                        <div class="fs24 color_9">{{$v['deduct_amount']}}</div>
                    </div>
                </div>
                @endforeach

            </div>

        </div>


    </div>
@endsection
