$(function () {
    var $prize = $(".play li").not("#btn"),//含谢谢参与的所有奖品
        $change = $("#change"),//显示剩余机会
        $btn = $("#btn"),//开始抽奖按钮
        dogeData = $btn.attr('data-status'),
        ids = $btn.attr('data-ids'),
        idsArr = ids.split(","),
        length = $prize.length,//奖品总数
        //data = {count: $change.text()},//次数
        bool = true,//判断是否可点击,true为可点击
        mark = 0,//标记当前位置，区间为0-7
        TimerSet;//定时器

    var data = {},
        prize = {},
        index = -1,  // 当前转动到哪个位置，起点位置
        count = 8,  // 总共有多少个位置
        timer = 0,  // 每次转动定时器
        speed = 200,  // 初始转动速度
        times = 0,    // 转动次数
        cycle = 30,   // 转动基本次数：即至少需要转动多少次再进入抽奖环节
        prize = 7,   // 中奖位置
        click = true,
        showToast = false; //显示中奖弹窗


    //点击抽奖
    $btn.click(function () {
        id = $(this).attr('info');
        routeurl = $(this).attr('routeurl');
        // console.log("api:",routeaaa)
        // console.log('user:', id);
        //$.post('lucklog',{id:id}) //抽奖记录做记录
        // console.log("ids",ids)
        //验证成就点是否充足
        let dogeArr = dogeData.split('-');
        let haveDoge = parseInt(dogeArr[0]);
        let needDoge = parseInt(dogeArr[1]);
        console.log("haveDoge:"+haveDoge +"   needDoge:"+needDoge)
        if(haveDoge<needDoge) {
            layer.open({
                content: '成就点余额不足，无法抽奖!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }


        // 当前未执行过抽奖
        if (bool && index==-1) {

            bool = false;
            $.ajax({
                url:routeurl,  //抽奖规则请求路由
                data:{ids: ids,'_token': $('meta[name="csrf-token"]').attr('content')},
                dataType:'json',
                type:'post',
                async:false,
                success: function (res) {
                    bool = true;
                    data = res;
                    console.log(res)
                    if(!data.code) {
                        layer.open({
                            content: data.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                        return false;
                    } else {
                        startLottery();
                    }

                    //data.count--;
                    //$change.html(data.count);

                    //静态抽奖
                    //clickFn(res.status);

                    console.log('ajax data:', res);
                    // rotateFn(res.status, "");
                },
                error:function(err){

                    console.log("err:",err);
                }
            });
        } else {
             return false;
        }
    });

    function startLottery(){
        //click = false;     //禁止转动时点击

        //每次点击重置当前位置
        if(index>0) {
            index = -1;
            let selected = $(".play li.select");
            if(selected) {
                selected.removeClass('select');
            }
        }

        if (!click) { return; };
        startRoll();
    };

    // 开始转动
    function startRoll () {
        times += 1 // 转动次数
        oneRoll() // 转动过程调用的每一次转动方法，这里是第一次调用初始化
        // 如果当前转动次数达到要求 && 目前转到的位置是中奖位置
        if (times > cycle + 10 && prize === index) {
            clearTimeout(timer);  // 清除转动定时器，停止转动
            times = 0;
            speed = 200;
            click = true;
            var that = this;
            setTimeout(function(){
                showToast = true;
            },500)

            if(!data.code){
                layer.open({
                   content: data.msg
                   ,skin: 'msg'
                   ,time: 1 //2秒后自动关闭
                });
	            setTimeout(function(){
	                fail(data);
	            },1000)

                return false;
            } else {
                let id = data.status + '';
                let index = idsArr.indexOf(id);
                if(data.code) {
                    prize = index
		            setTimeout(function(){
		                win(data);
		            },1000)

                    return false;
                } else {
                    layer.open({
                        content: '抽奖结果出错!请刷新后重试'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                    return false;
                }

            }
        } else {
            click = false;     //禁止转动时点击
            if (times < cycle) {
                speed -= 10;  // 加快转动速度
            } else if (times === cycle) {

                if (prize > 7) { prize = 7 };

            } else if (times > cycle + 10 && ((prize === 0 && index === 7) || prize === index + 1)) {
                speed += 110;
            } else {
                speed += 20;
            }
            if (speed < 40) {speed = 40}
            timer = setTimeout(startRoll, speed);
        }
        console.log('计算器：times', times, 'cycle:', cycle, 'prize:', prize, 'index:', index);
    };

    // 每一次转动
    function oneRoll() {
        let i = index; // 当前转动到哪个位置
        const c = count; // 总共有多少个位置
        i += 1;
        if (i > c - 1) { i = 0 }
        index = i;
        console.log(c, '位置：index', index);
        $prize.eq(i - 1).removeClass("select");
        $prize.eq(i).addClass("select");
    }

    //点击旋转
    function clickFn(mark) {
        clearInterval(TimerSet);//点击抽奖时清除定时器
        var random = [1, 2, 3, 4, 5, 6, 7, 8];//抽奖概率
         //data为随机出来的结果，根据概率后的结果
         random = random[Math.floor(Math.random() * random.length)];//1-8的随机数
         mark += random;
         mark %= 8;
         //控制概率，永远抽不中谢谢参与
         if (mark === 3) {//抽中第一个谢谢参与则向前一位
              random++;
              mark++;
         }
         if (mark === 6) {//抽中第二个谢谢参与则向后一位
              random--;
              mark--;
         }
         //默认先转4圈
         random += 32;//圈数 * 奖品总数
         //调用旋转动画
         for (var i = 1; i <= random; i++) {
              setTimeout(animate(), 2);//第二个值越大，慢速旋转时间越长
         }
        //停止旋转动画
        setTimeout(function () {
            console.log("中了" + mark);
            if(mark==-1){
                $(".winImg").attr({"src":"/block/images/choujiang/kulian.png"});
                fail();
                return false;
            }
            switch(mark) {
		     	case 1:
		     		$(".winImg").attr({"src":"/block/images/choujiang/konglongDan.png"});
		        break;
		     	case 10:
		     		$(".winImg").attr({"src":"/block/images/choujiang/jifen.png"});
		        break;
		        case 4:
		     		$(".winImg").attr({"src":"/block/images/choujiang/sanjiaolong.png"});
		        break;
		     	case 29:
		     		$(".winImg").attr({"src":"/block/images/choujiang/sulong.png"});
		        break;
		        case 30:
		     		$(".winImg").attr({"src":"/block/images/choujiang/jianlong.png"});
		        break;
		     	case 3:
		     		$(".winImg").attr({"src":"/block/images/choujiang/yilong.png"});
		        break;
		        case 2:
		     		$(".winImg").attr({"src":"/block/images/choujiang/canglong.png"});
		        break;
		     	case 5:
		     		$(".winImg").attr({"src":"/block/images/choujiang/beijia.png"});
		        break;
		     	default:
                   console.log("1")
		     	break;
			}
            setTimeout(function () {
                console.log("妖孽")
                bool = true;
                win();
            }, 10);
        }, 2);
    }

    //动画效果
    function animate() {
        return function () {
            //九宫格动画
            length++;
            length %= 8;
            $prize.eq(length - 1).removeClass("select");
            $prize.eq(length).addClass("select");
        }
    }

    //中奖信息提示
    $("#close,.win,.btn").click(function () {
        clearInterval(TimerSet);//关闭弹出时清除定时器
    });

    //奖品展示
    var show = new Swiper(".swiper-container", {
        direction: "horizontal",//水平方向滑动。 vertical为垂直方向滑动
        loop: false,//是否循环
        slidesPerView: "auto"//自动根据slides的宽度来设定数量
    });
});





