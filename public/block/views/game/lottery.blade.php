<!DOCTYPE html>
<html lang="zh-cmn-Hans" class="pixel-ratio-2 retina ios ios-11 ios-11-0 ios-gt-10 ios-gt-9 ios-gt-8 ios-gt-7 ios-gt-6" style="font-size: 75px;">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
    <title>幸运抽奖</title>


    <link rel="stylesheet" href="/block/css/choujiang/swiper.min.css">
    <link rel="stylesheet" href="/block/css/choujiang/common_mobile.css?version=1.0.0">
    <link rel="stylesheet" href="/block/css/choujiang/index.css?version=1.0.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="/block/js/jquery-2.1.4.js"></script>
    <script src="/block/js/jquery-weui.min.js"></script>
    <script src="/block/js/choujiang/swiper.jquery.min.js"></script>
    <script src="/block/js/choujiang/h5_game_common.js?version=1.0.0"></script>
    <script src="/block/js/choujiang/index.js?version=1.0.1"></script>
    <script src="/block/plugins/layer/layer.js"></script>

    <style>
        .play li.prize-btn {
            background-image: url("/block//images/choujiang/bg1.png");

        }
        .play li.select div {
            background-image: url("/block/images/choujiang/bg1.png");
        }
        .play li div {
            background-image: url("/block/images/choujiang/bg2.png");
        }
        .btnDiv .content {
            background-image: url("/block/images/choujiang/bg1.png");
        }
        #mask.fail .blin {
            background-image: url("/block/images/choujiang/gold1.png");
        }
        #wrap {
            /*margin-top: -0.01333333rem;*/
            background-image: url("/block/images/background.png");
            background-size: 100% 100%;
            min-height: 100vh;
            overflow: auto;
            padding-bottom: 50px;
        }

        .rule {
            left: 2.26666667rem;
            background-image: url("/block/images/choujiang/rule.png");
        }

        .my {
            right: 2.26666667rem;
            background-image: url("/block/images/choujiang/my.png");
        }
    </style>
    <!-- 移动端适配 -->
    <script>
        var html = document.querySelector('html');
        changeRem();
        window.addEventListener('resize', changeRem);

        function changeRem() {
            var width = html.getBoundingClientRect().width;
            html.style.fontSize = width / 10 + 'px';
        }
    </script>

    <style>
        .header {
            margin: 20px;
            text-align: center;
        }
        .header img {
            display: inline-block;
            width: 70%;
        }
        .bottom-tabbar a .icon {
            width: 0.8rem;
            height: 0.85rem;
            display: inline-block;
            margin-bottom: 0;
        }

        .game-reslist .res-list ul {
            color: #FFF;
            font-size: 0.3rem;
        }

        .game-reslist .res-list li {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }
        .game-reslist .res-list li .info-item {
            height: 0.8rem;
            line-height: 0.8rem;
            font-size: 0.4rem;
            flex: 1;
            overflow: hidden;
        }
        .game-reslist .res-list .list-header .info-item {
            font-size: 0.4rem;

        }
        .prize-name {
            color: #555;
            font-size: 0.4rem;
            text-align: center;
        }
    </style>


<body>
<div class="page seckill">

    <div class="page-bd">
        <!-- 页面内容 -->
        <!--<div class="topimg" >-->
        <!--<img src="/block/images/lucky.png" alt="">-->
        <!--</div>-->

        <div id="wrap">
            <!--头部-->
            <!--头部-->
            <div class="header">
                <img src="/block/images/choujiang/luckgame-header.png" class="headerImg" />
            </div>
            <h2 class="txtChange">
                <p>剩余成就点： <span id="change"> {{$data['havedoge']}} </span></p>
            </h2>
            <!--主体-->
            <div class="main">
                <!--游戏区域-->
                <div class="box">

                    <!--开始按钮-->
                    <div class="btnDiv" id="btn" info="{{$data['uid']}}" routeurl="{{route("block.game.lotterypost")}}" data-status="{{$data['cha']}}" data-ids="{{$data['ids']}}">
                        <div class="content"  >
                            <p>抽奖</p>
                            <p>消耗{{$data['needdoge']}}积分</p>
                        </div>
                    </div>
                    <!--开始按钮-->


                    <!--九宫格-->
                    <ul class="play clearfix">
                        @foreach($list as $v)
                            <li class="prize" data-id="{{$v['id']}}">
                                <div>
                                    <img src="{{$v['images']}}" class="prizeImg" />
                                    <span class="game-name">{{$v['name']}}</span>
                                </div>
                            </li>
                        @endforeach

                    </ul>

                </div>

                <!--游戏规则弹窗-->
                <div id="mask_rule">
                    <div class="tipTitleDiv">
                        <img src="/block/images/choujiang/gamerule-header.png" class="tipTitle" />
                    </div>
                    <div class="box_rule">
                        <p>1.每位玩家每日限抽奖1次</p>
                        <p>2.奖品为已发行恐龙与积分(随机抽取)</p>
                        <p>3.奖品会在2小时内发放至玩家账户，请注意查收</p>
                        <p>4.每100成就点兑换一次抽奖次数。</p>
                    </div>
                </div>


                <!--中奖几率-->
                <div id="mask_rule" class="game-reslist">
                    <div class="tipTitleDiv">
                        <img src="/block/images/choujiang/gamelist.png" class="tipTitle" />
                    </div>
                    <div class="box_rule res-list">
                        <ul>
                            @empty($reslist)
                            <li class="empty"><span>没有数据</span></li>
                            @else

                            <li class="list-header">
                                <span class="info-item">编号</span>
                                <span class="info-item">奖品</span>
                                <span class="info-item">时间</span>
                            </li>
                            @foreach($reslist as $v)

                            <li>
                                <span class="info-item">{{$v['id']}}</span>
                                <span class="info-item">{{$v['prizename']}}</span>
                                <span class="info-item">{{$v['created_at']}}</span>
                            </li>
                            @endforeach
                            @endempty
                        </ul>
                    </div>
                </div>


            </div>
        </div>
        <!--中奖提示-->
        <!--<div id="mask" class="fail">-->
        <div id="mask">
            <div class="blin"></div>
            <div class="caidai"></div>
            <div class="winning">
                <div class="red-head"></div>
                <div class="red-body">
                    <img src="/block/images/choujiang/beijia.png" class="winImg" />
                    <div class="prize-name"></div>
                </div>
                <div id="card">
                    <a href="" target="_self" class="win">恭喜您中奖了!</a>
                </div>
                <a href="" target="_self" class="btn">确定</a>
                <span id="close"></span>
            </div>
            <div class="failDiv">
                <img src="/block/images/choujiang/bottom1.png" class="failImg" />
                <a href="" target="_self" class="btnFail">确定</a>
                <span id="close"></span>
            </div>
        </div>
    </div>
    <!--<div class="tpoimg">-->
    <!--<img src="/block/images/seckillTop.png" alt="">-->
    <!--<p class="fw_b color_w" id="time">已结束</p>-->
    <!--</div> -->
    <!--<div class="selectBox">-->
    <!--<img src="/block/images/seckill01.png" alt="">-->
    <!--<img src="/block/images/seckill02.png" alt="">-->
    <!--<img src="/block/images/seckill03.png" alt="">-->
    <!--<img src="/block/images/seckill04.png" alt="">-->
    <!--<img src="/block/images/seckill05.png" alt="">-->
    <!--<img src="/block/images/seckill06.png" alt="">-->
    <!--<img src="/block/images/seckill07.png" alt="">-->
    <!--<img src="/block/images/seckill08.png" alt="">-->
    <!--</div>-->
    <!--<div class="btnBox">-->
    <!--<button class="fs30 fw_b color_3 but" onclick="click_luck()" style="display:none">立即秒杀</button>-->
    <!--</div>-->
    <!--<div class="seckillRule">-->
    <!--<div class="title fs36 fw_b color_w ">活动规则</div>-->
    <!--<div class="rule"><p class="fs24 color_w fw_b">1</p><span class="fs26 color_w">每位玩家每日限抽奖1次</span></div>-->
    <!--<div class="rule"><p class="fs24 color_w fw_b">2</p><span class="fs26 color_w">奖品为已发行恐龙（恐龙随机）</span></div>-->
    <!--<div class="rule"><p class="fs24 color_w fw_b">3</p><span class="fs26 color_w">抽中奖品后，奖品会在2小时内发放至玩家账号，请注意查收</span></div>-->
    <!--</div> -->
</div>


<div class="bottom-tabbar-wrapper bottom-tabbar-box">
    <div class="background-block"></div>
    <div class="bottom-tabbar">
        <a href="{{route("block.home.index")}}" class="bottom-tabbar__item active">
                <span class="icon">
                    <img src="/block/images/bottom_icon01.png"/>
                    <img class="lhimg" src="/block/images/bottom_icon01_lh.png"/>
                </span>
            <p class="label">首页</p>
        </a>
        <a href="{{route("block.game.lottery")}}"  class="bottom-tabbar__item ">
              <span class="icon">
                  <img src="/block/images/bottom_icon02.png"/>
                  <img class="lhimg" src="/block/images/bottom_icon02_lh.png"/>
              </span>
            <p class="label">抽奖</p>
        </a>
        <a href="{{route("block.record.adopt")}}" class="bottom-tabbar__item ">
              <span class="icon">
                  <img src="/block/images/bottom_icon04.png"/>
                  <img class="lhimg" src="/block/images/bottom_icon04_lh.png"/>
              </span>
            <p class="label">鱼棠</p>
        </a>
        <a href="{{route("block.user.index")}}" class="bottom-tabbar__item ">
                <span class="icon">
                    <img src="/block/images/bottom_icon03.png"/>
                    <img class="lhimg" src="/block/images/bottom_icon03_lh.png"/>
                </span>
            <p class="label">我的</p>
        </a>
    </div>
</div>






</body></html>
