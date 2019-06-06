/**
 * Created by Administrator on 2017/1/17.
 */

/**
 * 播放视频
 */
function playVideo() {
    // 视频框
    var video_box = document.getElementsByClassName("video_box")[0];
    // 底部背景
    var popBg = document.getElementsByClassName("popBg")[0];

    var videoView = document.getElementById("videoView");
    videoView.play();

    video_box.style.display = "block";
    popBg.style.display = "block";
}

/**
 * 关闭视频
 */
function closeVideo() {
    var close = document.getElementById("video_close");

    // 视频框
    var video_box = document.getElementsByClassName("video_box")[0];
    // 底部背景
    var popBg = document.getElementsByClassName("popBg")[0];

    var videoView = document.getElementById("videoView");
    videoView.currentTime = 0;
    videoView.pause();

    video_box.style.display = "none";
    popBg.style.display = "none";
}

/**
 * 一键加群
 */
function addQQ(ios , android) {
    if(navigator.userAgent.match(/(iPhone|iPod|ios|iPad)/i))
    {
        window.open(ios);
    }else {
       window.open(android);
    }
}

/**
 * 点击资讯按钮 的切换
 * @param number
 */
function setStyle(number) {

    var type = document.getElementById("type").children;
    var info = document.getElementById("info").children;

    for (var i = 0; i< type.length; i++)
    {
        if(i+1 == number)
        {
            type[i].className = "curr";
            info[i].style.display = "block";
        }else
        {
            type[i].className = "";
            info[i].style.display = "none";
        }
    }
}

/**
 * 点击资讯类型按钮 切换 颜色 和 资讯内容
 * @param number
 */
function setNewsInfo(name) {
    var type = document.getElementById("type1").children;

    var type_list = new Array("dzkm_zx","dzkm_xw","dzkm_gg","dzkm_hd","dzkm_gl");

    for (var i = 0; i < type_list.length; i++){
        if(name == type_list[i])
        {
            type[i].className = "curr";
        }
    }

    // for (var i = 0; i< type.length; i++)
    // {
    //     if(i+1 == number)
    //     {
            
    //     }else
    //     {
    //         type[i].className = "";
    //     }
    // }
}

function set_game_bg(bg_url)
{
    var bg = document.getElementById('bg');
    bg.style.background="url(" + bg_url + ")center no-repeat";
    bg.style.backgroundColor = "#111015";
    bg.style.backgroundPositionY = "60px";
    bg.style.backgroundSize = "1920px 1300px";
}

/**
 * 当屏幕发生变化后
 */
window.addEventListener("orientationchange", function() {
    // location.reload();
}, false);

