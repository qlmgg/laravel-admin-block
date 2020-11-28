@extends('layouts.app')

@section('header')

    <link rel="stylesheet" href="/block/css/all2.css">
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/custom-topnav.css">
@endsection

@section('appcontent')

    <div class="page PIGmoney">

        <div class="page-hd">
            <div class="header bor-1px-b">
                <div class="header-left">
                    <a href="javascript:history.go(-1)" class="left-arrow"></a>
                </div>
                <div class="header-title">
                    {{$html['currencychinese']}}
                </div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>

        <div class="page-bd moneylist" style="overflow: auto;">

            <div class="top bor_b">


                <div class="fw_b color_3 num pay_points">
                    {{$html['pricecount']}}
                </div>
                <div class="fs28 color_9">
                    当前{{$html['currencychinese']}}
                </div>



            </div>





            <div class="boxlist">
                @foreach($log as $v)
                    <div class="box weifen">
                        <div class="info">
                            <div class="row">
                                <div class="fs28 fw_b color_3">{{$v['remark']}}</div>
                                <div class="fs24 color_9">{{$v['created_at']}}</div>
                            </div>
                            <div class="fs36 fw_b color_r">{{$v['much']}}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>





    </div>
    <style>
        .page {
            background-image: url('/block/images/background.png');
        }

    </style>
@endsection
