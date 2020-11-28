<!-- 保存在 resources/views/layouts/app.blade.php 文件中 -->

<html>
<head>
    <title>@yield('title')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">

    <link rel="stylesheet" href="/block/plugins/layer/need/layer.css">
    <script src="/block/plugins/layer/layer.js"></script>
    <script src="/block/plugins/iscroll/iscroll.js"></script>

    <script src="/block/js/jquery-2.1.4.js"></script>
    <script src="/block/js/jquery-weui.min.js"></script>
    <script src="/block/js/fastclick.js"></script>
    <script src="/block/js/swiper.js"></script>
    <script src="/block/js/block.js"></script>
    <script src="/block/js/jquery.cookie.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.19.2/axios.js"></script>
    <script src="https://cdn.bootcss.com/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('header')
    <script>


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function axiosresponse(response) {
            var res = response.data
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
        }

        function layerinfo(msg) {
            layer.open({
                content: msg
                , skin: 'msg'
                , time: 2 //2秒后自动关闭
            });
        }
        function kaifazhong() {
            layer.open({
                content: '正在努力开发中'
                , btn: '我知道了'
            });
        }


    </script>
</head>
<body>



<div class="appcontent">
    @yield('appcontent')
</div>


@section('appfooter')
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
            <a href="{{route("block.game.lottery")}}" class="bottom-tabbar__item ">
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
                <p class="label">神兽</p>
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
@show


<script>


    function loadimg(obj, namepath) {
        var fm = new FormData();
        fm.append('file', obj.files[0]);
        fm.append("name", namepath);
        var url = '{{route("block.file.upload")}}';
        axios({
            url: url,
            method: 'post',
            data:fm,
            onDownloadProgress (progressEvent) {
                let complete = (progressEvent.loaded / progressEvent.total * 100 | 0) + '%'
                console.log('complete' + complete)
            }
        }).then((response)=>{
            var res = response.data
            if(res.code){
                $("#asyncuploadimgsrc").attr("src", res.data.url);
                $("#asyncuploadimghiddenfilepath").attr("value", res.data.pathname);
            }
        }).catch(err=>{
            console.log(err)
        });
    }



</script>




</body>
</html>
