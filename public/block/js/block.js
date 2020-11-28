
$(function() {
    FastClick.attach(document.body);
});




//ajax跳转
function ajaxhref(routing) {
    $.ajax({
        url: routing,
        type: "POST",
        success: function (res) {
            setInterval(function () {
                if (res.url != null) {
                    self.location.href = res.url
                }
                $.hideLoading();
            }, 1000)
            $.showLoading(res.msg);

        },
        error: function () {
            $.alert("正在升级中")
        }
    })
}


//单个删除
function  ajaxdel(routing,id) {
    $.ajax({
        url: routing,
        data: {"id": id},
        type: 'POST',
        success: function (res) {
            if (res.code) {
                setTimeout(function () {
                    if(res.url){
                        self.location.href = res.url
                    }else{
                        window.location.reload();
                    }
                }, 2000)
            }
            $.alert(res.msg)
        },
        error: function () {
            $.alert("系统在维护")
        }
    });
}

// 幻灯片查看图片
function openimage(imagepath) {
    $.photoBrowser({
        items: [imagepath]
    }).open()
}
