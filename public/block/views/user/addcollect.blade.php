@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">
    <link rel="stylesheet" href="/block/css/verified.css">
@endsection

@section('appcontent')
    <div class="page verify">

        <div class="page-hd">
            <div class="header bor-1px-b">
                <div class="header-left">
                    <a href="javascript:history.go(-1)" class="left-arrow"></a>
                </div>
                <div class="header-title">
                    @if(request("source"))
                        实名认证
                    @else
                        添加收款方式
                    @endif
                </div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>


        <div class="page-bd">
            <!-- 页面内容 -->
            <div class="fromBox">

                @if(request("source"))
                    <select class="weui-select" name="way" style="display: none">
                        <option value="0">身份验证</option>
                    </select>
                @else
                    <div class="weui-cells__title  fs28 color_3 fw_b ">收款方式</div>
                    <div class="weui-cells weui-cells_form">
                        <div class="weui-cell">
                            <select class="weui-select" name="way">
                                @foreach( $paymentmethod as $k=> $v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif


                <div class="weui-cells__title  fs28 color_3 fw_b">姓名</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b mobile" name="name" type="text" placeholder="请输入姓名">
                        </div>
                    </div>
                </div>

                <div class="weui-cells__title  fs28 color_3 fw_b ">绑定手机号</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b account" name="phone" type="text" placeholder="请输入手机号">
                        </div>
                    </div>
                </div>


                <div class="weui-cells__title  fs28 color_3 fw_b ">
                    @if(request("source"))
                        身份证号
                    @else
                        账号
                    @endif
                </div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b account" name="account" type="text" placeholder="请输入账号">
                        </div>
                    </div>
                </div>


                @if(request("source"))
                    <input id="asyncuploadimghiddenfilepath" type="hidden" value="/default/block/image/shenfenzheng.png">
                @else
                    <div class="img1">
                        <div class="weui-cells__title  fs28 color_3 fw_b imgs1">收款账户 支付宝/微信</div>
                        <div class="weui-cells weui-cells_form">
                            <div class="weui-cell fileBox" style="padding-left: 0px">
                                <form id="head_pic" method="post" enctype="multipart/form-data">
                                    <div class="weui-cell__bd uploadImg">
                                        <img src="/block/images/uploadImg.png"
                                             style="width:2rem;height: 2rem;display:block;" id="asyncuploadimgsrc">
                                        <input id="uploaderInput" class="weui-uploader__input imgs"
                                               onchange="loadimg(this,'payment')" type="file" accept="image/*">
                                        <input id="asyncuploadimghiddenfilepath" type="hidden">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="butBox">
                <div class="but" id="tijiaoshengren">提交</div>
            </div>
        </div>


        <script>


            $("#tijiaoshengren").on('click', function () {
                var way = $("select[name='way']").val()
                var name = $("input[name='name']").val()
                var phone = $("input[name='phone']").val()
                var account = $("input[name='account']").val()
                var filepath = $("#asyncuploadimghiddenfilepath").val()

                if (name.length == 0) {
                    $.alert("姓名未填写");
                    return;
                }
                if (phone.length == 0) {
                    $.alert("手机号未填写");
                    return;
                }
                if (account.length == 0) {
                    $.alert("绑定账号未填写");
                    return;
                }
                if (filepath.length == 0) {
                    $.alert("图片未上传");
                    return;
                }

                $.ajax({
                    url: "{{route("block.user.addcollect")}}",
                    data: {"way": way, "name": name, "phone": phone, "account": account, "filepath": filepath},
                    type: 'POST',
                    success: function (res) {
                        if (res.code) {
                            setTimeout(function () {
                                self.location.href = res.url
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
