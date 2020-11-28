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
                找回密码
            </div>
            <div class="weui-cells weui-cells_form">

                <div class="row_div">
                    <div class="weui-cell__hd Box">
                        <label class=" fs24 color_6">手机号</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="text" pattern="\d*" minlength="11" maxlength="11"
                               name="username" placeholder="请输入手机号">
                    </div>
                    <div class="weui-cell__ft">
                        <button class="weui-vcode-btn sendcode">获取验证码</button>
                    </div>
                </div>

                <div class="row_div">
                    <div class="Box"><span class="fs24 color_6">验证码</span></div>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b mobile" type="text" name="code" placeholder="请输入验证码">
                            <input  type="hidden" name="codekey" >
                        </div>
                        <div class="weui-cell__ft"></div>
                    </div>
                </div>

                <script>
                    $(function () {
                        $(".sendcode").click(function () {
                            var username = $("input[name='username']").val()
                            if (username.length !== 11) {
                                $.alert("手机号输入有误")
                                return
                            }
                            axios.post("{{route("api.sendcode")}}",{'phone':username}).then(function (resp) {
                                var res = resp.data;
                                if(res.code){
                                    $("input[name='codekey']").val(res.data.codekey);
                                    daojishi(res.data.ttl)
                                }
                            }).catch(function (e) {
                                console.log(e)
                            })
                        })
                    });
                    function daojishi(curCount) {
                        // var curCount = 60
                        snapupresultTime = setInterval(function () {
                            if (curCount > 0) {
                                $(".sendcode").html(+curCount + "秒后再次发送")
                                --curCount;
                            } else {
                                $(".sendcode").html("获取验证码")
                                clearInterval(snapupresultTime);
                            }
                        }, 1000)
                    }
                </script>


                <div class="row_div">
                    <div class="Box"><span class="fs24 color_6">密码</span></div>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b mobile" type="password" name="password1"
                                   placeholder="请输入密码">
                        </div>
                        <div class="weui-cell__ft"></div>
                    </div>
                </div>
                <div class="row_div">
                    <div class="Box"><span class="fs24 color_6">新密码</span></div>
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b mobile" type="password" name="password2"
                                   placeholder="请输入密码">
                        </div>
                        <div class="weui-cell__ft"></div>
                    </div>
                </div>
            </div>
            <br>
            <div class="butBox">
                <div class="but " id="dopwd">重置密码</div>
            </div>
        </div>
    </div>

    <script>

        $("#dopwd").click(function () {
            var username = $("input[name='username']").val()
            var code = $("input[name='code']").val()
            var password1 = $("input[name='password1']").val()
            var password2 = $("input[name='password2']").val()
            var codekey = $("input[name='codekey']").val()
            if (username.length !== 11) {
                $.alert("手机号输入有误");
                return;
            }
            if (!password1 || !password2 || password1 !== password2) {
                $.alert("密码填写有误");
                return;
            }
            var data = {"username": username, 'code': code, "password1": password1, "password2": password2,"codekey":codekey}
            axios.post("{{Request::url()}}", data).then(function (res) {
                axiosresponse(res)
            }).catch(function (e) {
                console.log(e)
            });

        })
    </script>




@endsection
@section('appfooter')
@endsection
