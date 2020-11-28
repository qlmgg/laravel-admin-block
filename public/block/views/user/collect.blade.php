@extends('layouts.app')


@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">
@endsection


@section('appcontent')


    <div class="page appoint">

        <div class="page-hd">
            <div class="header bor-1px-b">
                <div class="header-left">
                    <a href="javascript:history.go(-1)" class="left-arrow"></a>
                </div>
                <div class="header-title">资产管理</div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>


        <div class="page-bd">


            <div class="weui-cells">


                @foreach($list as $v)
                <div class="weui-cell box bankCard">
                    <div class="weui-cell__hd ">
                        <img src="{{$v['pic']}}" alt="点击放大" width="90px" height="90px" onclick="openimage('{{$v['pic']}}')">
                    </div>
                    <div onclick="listclick('{{$v['id']}}')">
                        <div class="weui-cell__bd">
                            <div class="fs28 color_9">姓名:<span class="color_3">{{$v['realname']}}</span></div>
                            <div class="fs28 color_9">账号:<span class="color_3">{{$v['account']}}</span></div>
                            <div class="fs28 color_9">账户类型:<span class="color_3">{{$v['wayinfo']}}</span></div>
                        </div>
                        <div class="color_9">
                                审核状况：<span class="color_3">{{$v['statusinfo']}}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route("block.user.addcollect") }}" class="butBox"><div class="but">添加</div></a>
        </div>
    </div>

    <script>
        function listclick(id){
            $.actions({
                actions: [{
                    text: "删除",
                    onClick: function() {
                        ajaxdel("{{route('block.user.delcollect')}}",id);
                    }
                }]
            });
        }
    </script>


@endsection
