@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">

@endsection

@section('appcontent')

    <style>

        .makeOver {
            padding: 0 .2rem;
            margin-bottom: .2rem;
            background: rgba(197, 80, 28, 0.65);
        }

        .makeOverPay {
            padding: 0 .2rem;
            margin-bottom: .2rem;
            background: rgba(197, 80, 28, 0.65);
        }

        .fs26 {
            color: #FFF;
        }

        .page-bd {
            overflow: auto;
        }

        .weui-cell__bd img {
            /* width: 40%; */
            margin: 0 auto;
        }
    </style>
    <div class="page verify">

        <div class="page-hd">
            <div class="header bor-1px-b">
                <div class="header-left">
                    <a href="javascript:history.go(-1)" class="left-arrow"></a>
                </div>
                <div class="header-title">区块支付详情</div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>

        <div class="page-bd">
            <div class="makeOver color_3">
                <div class="fs26"><span class="txtTitle">订单时间：</span><span
                        class="fw_b establish_time">{{$recording['created_at']}}</span>
                </div>
                <div class="fs26"><span class="txtTitle">转让方：</span><span
                        class="fw_b seller">{{$recording['releaseuser']['name']}}</span></div>
                <div class="fs26"><span class="txtTitle">转让方联系电话：</span><span
                        class="fw_b seller_mobile">{{$recording['releaseuser']['phone']}}</span></div>
                <div class="fs26"><span class="txtTitle">领养方：</span><span
                        class="fw_b buyer">{{$recording['user']['name']}}</span></div>
                <div class="fs26"><span class="txtTitle">领养方联系电话：</span><span
                        class="fw_b buyer_mobile">{{$recording['user']['phone']}}</span></div>
                <div class="fs26"><span class="txtTitle">金额：</span><span
                        class="fw_b pig_price">{{$recording['price']}}</span></div>
            </div>


            <div class="makeOverPay">
                @if(isset($recording['releaseuseridentity']))
                    @foreach($recording['releaseuseridentity'] as $v)
                        <p class="fs30 fw_b color_3 payment_list" style="color: #FEE900;">转让方收款账号</p>
                        <div class="payBox"><img src="{{$v['image']}}" alt="{{$v['realname']}}" width="50px"
                                                 height="50px" onclick="openimage('{{$v['image']}}')">
                            <div>
                                <div class="fs26"><span class="txtTitle">账户名称：</span><span>{{$v['realname']}}</span>
                                </div>
                                <div class="fs26"><span class="txtTitle">账户号：</span><span>{{$v['account']}}</span></div>
                                <div class="fs26"><span class="txtTitle">账户类型：</span><span>{{$v['wayinfo']}}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>


            <div class="fromBox">
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cells__title  fs28 color_3 fw_b" style="text-align: center">付款凭证</div>
                </div>
                <div class="weui-cells weui-cells_form" style="padding: 0 .2rem;text-align: center">
                    <div class="weui-cell__bd uploadImg">
                        @if(isset($recording['reviewarr']))
                            <img src="{{$recording['reviewarr']['image']}}"
                                 style="width:2rem;height: 2rem;display:block;" id="asyncuploadimgsrc" onclick="openimage('{{$recording['reviewarr']['image']}}')">
                        @else
                            <img src="/block/images/uploadImg.png"
                                 style="width:2rem;height: 2rem;display:block;" id="asyncuploadimgsrc">
                        @endif
                    </div>
                </div>
            </div>

            @isset($recording['reviewarr'])

                @switch($recording['reviewarr']['status'])
                    @case(0)
                    <div class="butBox" id="submitconfirm">
                        <div class="but">确认</div>
                    </div>
                    @break
                    @case(1)
                    <div>
                        <div class="butBox">
                            <div class="but">已通过</div>
                        </div>
                    </div>
                    @break
                    @case(2)
                    <div>
                        <div style="color: #FEE900;font-size: 0.26rem;text-align: center">
                            拒绝理由：{{$recording['reviewarr']['fail_reason']}}
                        </div>
                    </div>
                    @break
                @endswitch
            @endisset
        </div>
        <style>
            .weui-input {
                color: red;
            }
        </style>
        <script>
            $("#submitconfirm").on('click', function () {
                $.modal({
                    title: "",
                    text: "请对您的转让作出正确的选择，选择通过不可再次操作",
                    buttons: [
                        {
                            text: "通过", onClick: function () {
                                ajaxtijiao({"id": "{{$recording['id']}}", "reason": "pass", "method": "pass"})
                            }
                        },
                        {
                            text: "拒绝", onClick: function () {
                                $.prompt({
                                    text: "",
                                    title: "输入拒绝的理由",
                                    onOK: function (text) {
                                        ajaxtijiao({"id": "{{$recording['id']}}", "reason": text, "method": "fail"})
                                    },
                                    onCancel: function () {
                                        console.log("取消了");
                                    },
                                });

                            }
                        },
                        {text: "取消", className: "default"},
                    ]
                });
            });

            function ajaxtijiao(data) {
                $.ajax({
                    url: "{{Request::url()}}",
                    data: data,
                    type: 'POST',
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
                        $.alert(res.msg)
                    },
                    error: function () {
                        $.alert("提交失败")
                    }
                });
            }
        </script>


    </div>



@endsection
