@extends('layouts.app')


@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">
    <link rel="stylesheet" href="/block/css/login.css">
@endsection

@section('appcontent')
    <div class="page">


        <div class="page-bd login">
            <!-- 页面内容 -->
            <div class="logoImgDiv">
                <img src="/block/images/logo.png" class="logoImg"/>
            </div>
            <div class="logoImgDiv" style="color: red;font-size: 0.3rem">
                扫码注册
            </div>
            <div class="weui-cells weui-cells_form">

                <div class="row_div">
                    <div class="Box"><span class="fs24 color_6">手机号</span></div>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b mobile" type="text" pattern="\d*" minlength="11" maxlength="11"  name="username" placeholder="请输入手机号">
                        </div>
                        <div class="weui-cell__ft"></div>
                    </div>
                </div>
                <div class="row_div">
                    <div class="Box"><span class="fs24 color_6">密码</span></div>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b mobile" type="password" name="password" placeholder="请输入密码">
                        </div>
                        <div class="weui-cell__ft"></div>
                    </div>
                </div>
                <div class="row_div1" style="display: none">
                    <div class="Box"><span class="fs24 color_6">邀请人</span></div>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b mobile" type="text" name="rcode" value="{{ $rcode }}">
                        </div>
                        <div class="weui-cell__ft"></div>
                    </div>
                </div>
            </div>
            <br>
            <div class="butBox"><div class="but " id="doregister">注册</div></div>
        </div>
    </div>

    <script>

        $("#doregister").click(function () {
            var username=$("input[name='username']").val()
            var rcode=$("input[name='rcode']").val()
            var password=$("input[name='password']").val()
            if(username.length !==11){
                $.alert("手机号输入有误");
                return;
            }
            if(username.length !==11){
                $.alert("密码未填写");
                return;
            }
            if(!rcode){
                $.alert("请进行扫码注册");
                return;
            }
            var data = {"username":username,"rcode":rcode,'password':password}
            axios.post("{{route("block.Auth.doregister")}}",data).then(function (res) {
                axiosresponse(res)
            }).catch(function (e) {
                console.log(e)
            });

        })

    </script>




@endsection
@section('appfooter')
@endsection
