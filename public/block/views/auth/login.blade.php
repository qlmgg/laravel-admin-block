@extends('layouts.app')


@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">
    <link rel="stylesheet" href="/block/css/login.css">
@endsection

@section('appcontent')
    <div class="page">

        <style>
            .logoImg {
                width: 2.2rem;
            }
        </style>
        <div class="page-bd login">
            <!-- 页面内容 -->
            <div class="logoImgDiv">
                <img src="/block/images/logo.png" class="logoImg"/>
            </div>
            <div class="weui-cells weui-cells_form">
                <div class="row_div">
                    <div class="Box"><span class="fs24 color_6">手机号</span></div>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b mobile" type="text" pattern="\d*" minlength="11"
                                   maxlength="11" name="username" placeholder="请输入手机号">
                        </div>
                        <div class="weui-cell__ft"></div>
                    </div>
                </div>
                <div class="row_div">
                    <div class="Box pwd"><span class="fs24 color_6">密码</span></div>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 pwdInput fw_b password" name="password" type="password"
                                   placeholder="请输入密码">
                        </div>
                        <div class="weui-cell__ft"><img src="/block/images/ere_no.png" data-style="no" alt=""
                                                        class="ereimg"></div>
                    </div>
                </div>

                <div class="tipDiv">
                    <div class="Box pwd chkPwd">
                        <input type="checkbox" name="remember" value="1" id="remember_pwd" class="remember_pwd"
                               checked="checked">
                        <span class="fs24">记住密码</span>
                    </div>
                    <div class="fwDiv">
                        <a href="{{route("block.Auth.pwd")}}" class="findpwd fw_b fs24 color_r">忘记密码？</a>
                    </div>
                </div>
            </div>

            <br>

            <div class="butBox" id="dologin">
                <div class="but">登录</div>
            </div>
            <div class="butBox register">

                {{!! $butBox !!}}

            </div>
        </div>
    </div>

    <script>

        $("#dologin").click(function () {
            var username = $("input[name='username']").val()
            var password = $("input[name='password']").val()
            var remember = $("input[name='remember']").val()
            if (username.length !== 11) {
                $.alert("手机号不正确");
                return;
            }
            if (password.length == 0) {
                $.alert("密码不能为空");
                return;
            }
            savepassword($("input[name='remember']"), username, password)
            axios.post('{{route("block.Auth.dologin")}}', {
                username: username,
                password: password,
                remember: remember,
            }).then(function (response) {
                axiosresponse(response);
            }).catch(function (error) {
                console.log(error);
            });
        });




        var aeskey  = "{{env('APP_KEY')}}";
        var _user_info = localStorage.getItem("_user_info");
        if(_user_info != null){
            $('#remember_pwd').attr('checked','checked')
            var jsonobj = JSON.parse(CryptoJS.AES.decrypt(_user_info, aeskey).toString(CryptoJS.enc.Utf8));
            $("input[name='username']").val(jsonobj.usename)
            $("input[name='password']").val(jsonobj.password)
        }
        //保存密码
        function savepassword(rememberobj, user, password) {
            var checked = rememberobj[0].checked;
            if (checked) {
                var _tmp_userinfo = {}
                _tmp_userinfo.usename = user
                _tmp_userinfo.password = password
                var _user_info = CryptoJS.AES.encrypt(JSON.stringify(_tmp_userinfo),aeskey);
                localStorage.setItem("_user_info",_user_info);
            } else {
                localStorage.setItem("_user_info", null);
            }
        }

    </script>




@endsection



@section('appfooter')
@endsection
