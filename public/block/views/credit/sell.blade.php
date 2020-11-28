@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">
@endsection

@section('appcontent')
    <div class="page">
        <div class="page-hd">
            <div class="header bor-1px-b">
                <div class="header-left">
                    <a href="javascript:history.go(-1)" class="left-arrow"></a>
                </div>
                <div class="header-title">资产出售</div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>

        <div class="fromBox">

            <div class="weui-cells__title fs28 color_3 fw_b">可出售资产选择</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="credit" id="credit">
                            @foreach($okcredit as $index=>$name)
                            <option value="{{$index}}">{{$name}}</option>
                           @endforeach
                        </select>
                    </div>
                </div>
            </div>


            <div class="weui-cells__title  fs28 color_3 fw_b">出售收益</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <input class="weui-input fs28 fw_b mobile" name="number" type="text" onblur="checknumber()" placeholder="请输入出售收益">
                    </div>
                </div>
            </div>
            <div class="weui-cells__title fs28 color_3 fw_b">级别选择</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="cardid" id="cardid">
                            <option>选择级别</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="butBox">
            <div class="but sellcard">确认出售</div>
        </div>

        <script >
            $(".sellcard").click(function () {
                var number=$("input[name='number']").val()
                var cardid=$("#cardid").val();
                var credit = $("#credit").val();
                $.ajax({
                    url : "{{Request::url()}}",
                    data:{"number":number,"cardid":cardid,'credit':credit},
                    type:'POST',
                    success:function (res) {
                        if(res.code){
                            setTimeout(function () {
                                if(res.url){
                                    self.location.href = res.url
                                }else{
                                    window.location.reload()
                                }
                            },2000)
                        }
                        $.alert(res.msg)
                    },
                    error:function () {
                        $.alert("确认失败")
                    }
                });

            });
            function checknumber() {
                var number=$("input[name='number']").val()
                $.ajax({
                    url:"{{route("block.card.worth")}}",
                    data:{"worth":number},
                    type:'POST',
                    success:function (res) {
                        console.log(res);
                        if(res.code && res.data){
                            $("#cardid").empty();
                            var op = "";
                            $.each(res.data,function (index,item) {
                                op += '<option value="'+item.id+'">'+item.name+'</option>'
                            });
                            $("#cardid").append(op)
                        }else{
                            $("#cardid").empty();
                            $("#cardid").append("<option>没有可选的选项</option>");
                        }
                    },
                    error:function () {
                        console.log('error');
                    }
                })
            }
        </script>
    </div>
@endsection
