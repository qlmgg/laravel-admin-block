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
                <div class="header-title">充值</div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>



        <div class="page-bd">

            @if($paycode)
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell fileBox" style="padding-left: 0px">
                    @foreach($paycode as $v)
                        <div class="weui-cell__bd uploadImg">
                            <img src="{{$v['image']}}" onclick="openimage('{{$v['image']}}')" style="width:2rem;height: 2rem;display:block;">
                        </div>
                    @endforeach
                </div>
            </div>
            @endif



            <style>
                .weui-photo-browser-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-image: url(../images/background.png);
                    display: none;
                    opacity: 0;
                    -webkit-transition: opacity .3s;
                    transition: opacity .3s;
                }
            </style>

            <!-- 页面内容 -->
            <div class="fromBox">
                <div class="weui-cells__title  fs28 color_3 fw_b">购买数量</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b mobile" type="number" name="number" pattern="[0-9]*" placeholder="请输入购买数量">
                        </div>
                    </div>
                </div>
                <div class="img1">
                    <div class="weui-cells__title  fs28 color_3 fw_b imgs1">上传付款凭证</div>
                    <div class="weui-cells weui-cells_form">
                        <div class="weui-cell fileBox" style="padding-left: 0px">
                            <form id="head_pic" method="post"  enctype="multipart/form-data">
                                <div class="weui-cell__bd uploadImg">
                                    <img src="/block/images/uploadImg.png" style="width:2rem;height: 2rem;display:block;" id="asyncuploadimgsrc">
                                    <input id="uploaderInput" class="weui-uploader__input imgs" onchange="loadimg(this,'review')" type="file" accept="image/*">
                                    <input id="asyncuploadimghiddenfilepath" type="hidden">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="butBox"><div class="but" id="submitconfirm">提交充值</div></div>
        </div>



        <script>


            $("#submitconfirm").on('click', function () {
                var numbers=$("input[name='number']").val()
                var filepath = $("#asyncuploadimghiddenfilepath").val()
                if(numbers == '' || numbers == undefined || numbers == null){$.alert("购买数量未填写");return;}
                if(filepath.length==0){$.alert("图片未上传");return;}
                $.ajax({
                    url : "{{Request::url()}}",
                    data:{"number":numbers,"filepath":filepath},
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


        </script>




    </div>



@endsection
@section('appfooter')
@endsection
