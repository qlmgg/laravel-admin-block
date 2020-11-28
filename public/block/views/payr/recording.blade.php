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
                <div class="header-title">区块支付</div>
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

                @if($recording['releaseuseridentity'])
                    @foreach($recording['releaseuseridentity'] as $v)
                        <p class="fs30 fw_b color_3 payment_list" style="color: #FEE900;">转让方收款账号</p>
                        <div class="payBox"><img src="{{$v['image']}}" alt="{{$v['realname']}}" width="180px"
                                                 height="200px" onclick="openimage('{{$v['image']}}')">
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

            @if($recording['releaseuseridentity'])
                <div class="fromBox">
                    <div class="weui-cells weui-cells_form">
                        <div class="weui-cells__title  fs28 color_3 fw_b" style="padding-left:0">付款凭证上传</div>
                    </div>
                    <div class="weui-cells weui-cells_form" style="padding: 0 .2rem;text-align: center">
                        <form id="head_pic" method="post" enctype="multipart/form-data">
                            <div class="weui-cell__bd uploadImg">

                                @if(isset($recording['reviewarr']))
                                    <img src="{{$recording['reviewarr']['image']}}"
                                         style="width:2rem;height: 2rem;display:block;" id="asyncuploadimgsrc" onclick="openimage('{{$recording['reviewarr']['image']}}')">
                                @else
                                    <img src="/block/images/uploadImg.png"
                                         style="width:2rem;height: 2rem;display:block;" id="asyncuploadimgsrc">
                                @endif
                                <input id="uploaderInput" class="weui-uploader__input imgs"
                                       onchange="loadimg(this,'recordingreview')" type="file" accept="image/*">
                                <input id="asyncuploadimghiddenfilepath" type="hidden">
                            </div>
                        </form>
                    </div>
                </div>



                @isset($recording['reviewarr'])
                    @if($recording['reviewarr']['status'] == 2)
                        <div style="color: #FEE900;text-align: center">
                            当前已被拒绝，请重新上传凭证
                            <br>
                            拒绝理由：{{$recording['reviewarr']['fail_reason']}}
                        </div>
                    @endif
                @endisset

                <div class="butBox" id="submitconfirm">
                    <div class="but">确认</div>
                </div>
            @else
                <div class="color_3" style="text-align: center">
                    转让方未填写支付方式/或已取消支付方式
                </div>
            @endif
        </div>
        <script>
            $("#submitconfirm").on('click', function () {
                var filepath = $("#asyncuploadimghiddenfilepath").val()
                if (filepath.length == 0) {
                    $.alert("图片未上传");
                    return;
                }
                $.ajax({
                    url: "{{Request::url()}}",
                    data: {"filepath": filepath, 'id': "{{$recording['id']}}"},
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
            });
        </script>


    </div>



@endsection
@section('appfooter')
@endsection
