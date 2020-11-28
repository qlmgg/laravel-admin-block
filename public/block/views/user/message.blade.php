@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/block/css/zpui.css">
    <link rel="stylesheet" href="/block/css/all.css">
    <style type="text/css">
        .news .Box .content {
            margin: 0.2rem 0.2rem 0;
            padding-bottom: 0.2rem;
            overflow: hidden;

        }

        .box_show {
            max-height: 1.5rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .cli_show {
            text-align: center;
            display: block !important;
            padding-top: 10px;
        }

    </style>
    <style>
        /* 隐藏顶部浮动栏选项  */
        body {
            position: static !important;
            top: 0px !important;
        }

        iframe.goog-te-banner-frame {
            display: none !important;
        }

        .goog-logo-link {
            display: none !important;
        }

        .goog-te-gadget {
            color: transparent !important;
            overflow: hidden;
        }

        .goog-te-balloon-frame {
            display: none !important;
        }

        /*使原始文本弹出窗口隐藏*/
        .goog-tooltip {
            display: none !important;
        }

        .goog-tooltip:hover {
            display: none !important;
        }

        .goog-text-highlight {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* 语言选择框颜色 */
        .goog-te-combo {
            background-color: #848CB5;
            border-radius: 8px;
        }

        .weui-navbar, .weui-navbar__item.weui-bar__item--on {
            background-color: rgba(197, 80, 28, 0.85);
        }

        .color_9 {
            color: #CCC;
        }

        .weui-navbar__item.weui-bar__item--on.color_9 {
            color: #FFF;
        }

        .news .Box {
            background-color: rgba(197, 80, 28, 0.65);
        }

        .news .Box .titie {
            margin-right: 0;
        }

        .header {
            margin: 0;
        }
    </style>
@endsection

@section('appcontent')
    <div class="page">
        <div class="page-hd">
            <div class="header bor-1px-b">
                <div class="header-left">
                    <a href="javascript:history.go(-1)" class="left-arrow"></a>
                </div>
                <div class="header-title">系统消息</div>
                <div class="header-right">
                    <a href="#"></a>
                </div>
            </div>
        </div>

        <style>
            .weui-media-box__desc {
                color: #fee900;
                font-size: 13px;
                line-height: 1.2;
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
            }

            .weui-media-box__title {
                font-size: 0.283333rem;
            }

            .weui-panel {
                background-color: rgba(197, 80, 28, 0.65);
            }
        </style>
        <div class="page-bd">

            <div class="weui-panel weui-panel_access">
                <div class="weui-panel__bd">
                    @foreach($message as $v)
                        <div class="weui-media-box weui-media-box_text">
                            <h4 class="weui-media-box__title">{{$v['title']}}</h4>
                            <p class="weui-media-box__desc">{{$v['content']}}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>


    </div>

@endsection
