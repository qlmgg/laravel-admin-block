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
                <div class="header-title">实名认证中心</div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>



        <div class="page-bd">
            <!-- 页面内容 -->
            <div class="fromBox">
                <div class="weui-cells__title  fs28 color_3 fw_b ">姓名</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b account" type="text" name="realname" placeholder="姓名" >
                        </div>
                    </div>
                </div>
                <div class="weui-cells__title  fs28 color_3 fw_b ">身份证号</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b account" name="idcard" type="text" placeholder="身份证号">
                        </div>
                    </div>
                </div>
            </div>
            <div class="butBox"><div class="but" id="tijiaoshengren">提交实名认证</div></div>
        </div>



<script>


    $("#tijiaoshengren").on('click', function () {
        var realname=$("input[name='realname']").val()
        var idcard=$("input[name='idcard']").val()
        if(realname.length==0){$.alert("姓名未填写");return;}
        if(idcard.length==0){$.alert("身份证未填写");return;}
        $.ajax({
            url : "{{route("block.user.verified")}}",
            data:{"realname":realname,"idcard":idcard},
            type:'POST',
            success:function (res) {
                if(res.code){
                    setTimeout(function () {
                        self.location.href = res.url
                    },2000)
                }
                $.alert(res.msg)
            },
            error:function () {
                $.alert("提交失败")
            }
        });

    });

    function loadimg(obj) {
        var fm = new FormData();
        // fm.append("'X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
        fm.append("payment",obj.files[0]);
        fm.append("method","text");
        $.ajax({
            url:"{{route("block.file.upload")}}",
            type: "POST",
            data :fm,
            contentType :false,
            processData :false,
            success:function (res) {
                if(res.code){
                    $("#imageurl").attr("src",res.data.url);
                    $("#verfilepath").attr("value",res.data.pathname)
                }
            },
            error:function () {
                $.alert("上传失败")
            }
        })
    }
</script>




</div>



@endsection
@section('appfooter')
@endsection
