@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/block/css/zpui2.css">
    <link rel="stylesheet" href="/block/css/all2.css">
    <link rel="stylesheet" href="/block/css/custom-topnav.css">
@endsection

@section('appcontent')

    <div class="page PIGmoney">

        <div class="page-hd">
            <div class="header bor-1px-b">
                <div class="header-left">
                    <a href="javascript:history.go(-1)" class="left-arrow"></a>
                </div>
                <div class="header-title">积分转赠</div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>


        <div class="page-bd moneylist">

            <div class="top bor_b">
                <div class="fw_b color_3 num pay_points">{{$htmlvar['jifen']}}</div>
                <div class="fs28 color_9">当前积分</div>
            </div>

            <div class="weifenTips">
                <div class="name"><span class="fs28 fw_b color_r">注意事项</span></div>
                <div class="list fs24 color_3">
                    <div>积分一旦转赠不可退回</div>
                </div>
            </div>


            <div class="fromBox">

                <div class="weui-cells__title  fs28 color_3 fw_b">对方手机</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b mobile" name="phone" type="text" placeholder="请输入对方手机号码">
                        </div>
                    </div>
                </div>

                <div class="weui-cells__title fs28 color_3 fw_b">转赠数量</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b number" name="number" type="text" placeholder="最大可转赠{{$htmlvar['jifen']}}">
                        </div>
                    </div>
                </div>

            </div>


            <div class="butBox">
                <div class="but transferreview">确认转赠</div>
            </div>

            <script>
                $(".transferreview").click(function () {
                    var phone=$("input[name='phone']").val()
                    var number=$("input[name='number']").val()
                    if(phone.length==0){
                        $.alert("手机号不能为空");
                        return;
                    }
                    if(number.length==0){
                        $.alert("数目未填写");
                        return;
                    }

                    $.ajax({
                        url:"{{Request::url()}}",
                        data:{"phone":phone,"number":number},
                        type:"post",
                        success:function (res) {
                            if(res.code){
                                setTimeout(function () {
                                    self.location.href = res.url
                                },1000)
                            }
                            $.alert(res.msg)
                        },
                        error:function () {
                            $.alert("系统正在升级")
                        }

                    })
                })
            </script>
        </div>


    </div>
    <style>
        .page {
            background-image: url('/block/images/background.png');
        }

        .PIGmoney .top {
            height: 1rem
        }

        .butBox .but {
            background-color: rgba(197, 80, 28, 1);
        }
    </style>
@endsection
