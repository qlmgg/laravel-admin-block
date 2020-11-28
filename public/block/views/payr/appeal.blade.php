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
                <div class="header-title">申诉</div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>


        <div class="page-bd">
            <div class="fromBox">
                <div class="top"><span class="fs26 fw_b color_r">申述后由客服人员介入调查！</span></div>
                <div class="weui-cells__title fs28 color_3 fw_b">区块编号</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b order_sn" type="text" value="{{$recording['hashblock']}}"
                                   disabled>
                        </div>
                    </div>
                </div>
                <div class="weui-cells__title fs28 color_3 fw_b">区块金额</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b pig_price" type="text" value="{{$recording['price']}}"
                                   readonly="">
                        </div>
                    </div>
                </div>
                <div class="weui-cells__title fs28 color_3 fw_b">对方手机号</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input class="weui-input fs28 fw_b nickname" type="text" value="{{$recording['userphone']}}"
                                   readonly="">
                        </div>
                    </div>
                </div>
                <div class="weui-cells__title fs28 color_3 fw_b">申诉理由</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell" style="height:auto">
                        <div class="weui-cell__bd">
                            @if($recording['appeal'])
                                <textarea class="weui-textarea fs28 remark" id="appealliyou" placeholder="请写下申述的理由"
                                          rows="3" style="background-color: #f5f5f5;" readonly>{{$recording['appeal']}}</textarea>
                            @else
                                <textarea class="weui-textarea fs28 remark" id="appealliyou" placeholder="请写下申述的理由"
                                          rows="3" style="background-color: #f5f5f5;"></textarea>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="butBox " id="tijiaoshenshu">
                <div class="but">提交申诉</div>
            </div>
        </div>


        <script>
            $("#tijiaoshenshu").on('click', function () {
                var appealliyou = $("#appealliyou").val()
                if (appealliyou.length == 0) {
                    $.alert("理由未填写");
                    return;
                }
                $.ajax({
                    url: "{{Request::url()}}",
                    data: {"reason": appealliyou, 'id': "{{$recording['id']}}"},
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
