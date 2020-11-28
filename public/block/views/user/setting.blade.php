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

                <div class="weui-cell fs28" id="updateuser">
                    <div class="weui-cell__bd color_9 ">手机号</div>
                    <div class="weui-cell__ft fw_b color_3 fs28 mobile">
                        {{$user->phone}}
                    </div>
                </div>
                <div class="weui-cell fs28" id="updateuser">
                    <div class="weui-cell__bd color_9 ">密码</div>
                    <div class="weui-cell__ft fw_b color_3 fs28 mobile">
                        ************
                    </div>
                </div>

                <div class="weui-cell fs28" id="updateuser">
                    <div class="weui-cell__bd color_9 ">姓名</div>
                    <div class="weui-cell__ft fw_b color_3 fs28 mobile">
                        {{$user->realname}}
                    </div>
                </div>
                <div class="weui-cell fs28" id="updateuser">
                    <div class="weui-cell__bd color_9 ">身份证号</div>
                    <div class="weui-cell__ft fw_b color_3 fs28 mobile">
                        {{$user->idcard}}
                    </div>
                </div>
                <a href="javascript:void(0)" class="weui-cell weui-cell_access fs28">
                    <div class="weui-cell__bd fw_b color_3">客服中心</div>
                    <div class="weui-cell__ft"></div>
                </a>
                <a href="javascript:void(0)" class="weui-cell weui-cell_access fs28">
                    <div class="weui-cell__bd fw_b color_3">帮助中心</div>
                    <div class="weui-cell__ft"></div>
                </a>
            </div>
            <div class="butBox"><div class="but" id="logout">安全退出</div></div>
        </div>

    </div>


    <script>

        // // $(document).on("click", "#show-prompt", function() {
        //     $.prompt({
        //         text: "名字不能超过6个字符，不得出现不和谐文字",
        //         title: "输入姓名",
        //         onOK: function(text) {
        //             $.alert("您的名字是:"+text, "角色设定成功");
        //         },
        //         onCancel: function() {
        //             console.log("取消了");
        //         },
        //         input: 'Mr Noone'
        //     });
        // // });


        $("#logout").click(function () {
            $.ajax({
                url : "{{route("block.user.logout")}}",
                data:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:'POST',
                success:function (res) {
                    console.log(res);
                    if(res.code){
                        setTimeout(function () {
                            self.location.href = res.url
                        },2000)
                    }
                    $.toast(res.msg);
                },
                error:function () {
                    console.log('error');
                }
            });
        })
    </script>
@endsection
