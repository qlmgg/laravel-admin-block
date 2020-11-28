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
                <div class="header-title">个人设置</div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>


        <div class="page-bd">
            <!-- 页面内容 -->
            <div class="weui-cells">
                <div class="weui-cell weui-cell_access fs28">
                    <div class="weui-cell__bd color_9 ">用户名</div>
                    <div class="weui-cell__ft fw_b color_3 fs28 nickname">
                        {{$user->name}}
                    </div>
                </div>


                <div class="weui-cell weui-cell_access fs28" id="showformbox">
                    <div class="weui-cell__bd color_9 ">登录密码</div>
                    <div class="weui-cell__ft fw_b color_3 fs28 passwd">
                        ***********
                    </div>
                </div>
            </div>



            <div class="fromBox forminput" >
                <div class="weui-cells__title fs28 color_3 fw_b">新密码</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b new_password" name="passwd" type="password" placeholder="请输入新的密码">
                        </div>
                    </div>
                </div>
                <div class="weui-cells__title fs28 color_3 fw_b">确认密码</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b confirm_password" name="passwd2" type="password" placeholder="请再次输入新的密码">
                        </div>
                    </div>
                </div>
                <div class="butBox"><div class="but" id="changepwd">修改密码</div></div>
            </div>

            <style>
                .forminput{
                    display: none;
                }
            </style>
            <script>

                $(function () {
                    $("#showformbox").click(function () {
                        $(".fromBox").removeClass("forminput");
                    })

                    $("#changepwd").click(function () {
                        var passwd=$("input[name='passwd']").val()
                        var passwd2=$("input[name='passwd2']").val()
                        if(passwd.length == 0 || passwd2.length  == 0 || passwd != passwd2){
                            $.alert("密码确认密码输入有误")
                        }

                        $.ajax({
                            url:"{{route("block.user.passwd")}}",
                            data:{"passwd":passwd,"passwd2":passwd2, 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success:function (res) {
                                if(res.code){
                                    setTimeout(function () {
                                        self.location.href = res.url
                                    },2000)
                                }
                                $.alert(res.msg);
                            },
                            error:function () {
                                console.log('error');
                            }
                        })

                    })

                })
            </script>


        </div>

    </div>



@endsection
