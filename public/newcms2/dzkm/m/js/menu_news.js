/**
 * Created by Administrator on 2017/1/19.
 */
var news = document.getElementsByClassName('ul_news');
var number = 0;
var my = 1;
news.onload = setTimeout(carouse,2000);
function carouse() {
    var news_list = news[0].children;
    var isnumber = number <= -20;
    if(!isnumber)
    {
        number -= 1;
        news[0].style.marginTop = number + "px";
        setTimeout(carouse,100);
    }else
    {
        number = 0;

        var li_1 = news_list[0].innerHTML;
        var li_2 = news_list[1].innerHTML;

        news[0].children[0].outerHTML = "<li>" + li_2 + "</li>";
        news[0].children[1].outerHTML = "<li>" + li_1 + "</li>";

        news[0].style.marginTop = "0px";
        setTimeout(carouse,2000);
    }
}

window.onload = function () {

    // 鼠标 停留在 客服 标签 显示 客服界面
    var game_kf = document.getElementById("game_kf");
    var c_service = document.getElementById("c_service");
    c_service.onmouseover = function () {
        // game_kf.style.display = "block";
        game_kf.style.visibility = "visible";
        game_kf.style.opacity = "1";
    }
    c_service.onmouseout = function () {
        // game_kf.style.display = "none";
        game_kf.style.visibility = "hidden";
        game_kf.style.opacity = "0";
    }

    game_kf.onmouseover = function () {
        // game_kf.style.display = "block";
        game_kf.style.visibility = "visible";
        game_kf.style.opacity = "1";
    }
    game_kf.onmouseout = function () {
        // game_kf.style.display = "none";
        game_kf.style.visibility = "hidden";
        game_kf.style.opacity = "0";
    }
    
    // 鼠标 停留在 游戏目录 标签 显示 游戏目录界面
    var menu_game = document.getElementsByClassName("menu_game")[0];
    var game_list = document.getElementById("game_list");

    menu_game.onmouseover = function () {
        // game_list.style.display = "block";
        game_list.style.visibility = "visible";
        game_list.style.opacity = "1";
    }
    menu_game.onmouseout = function () {
        // game_list.style.display = "none";
        game_list.style.visibility = "hidden";
        game_list.style.opacity = "0";
    }
    game_list.onmouseover = function () {
        // game_list.style.display = "block";
        game_list.style.visibility = "visible";
        game_list.style.opacity = "1";
    }
    game_list.onmouseout = function () {
        // game_list.style.display = "none";
        game_list.style.visibility = "hidden";
        game_list.style.opacity = "0";
    }

    var url = document.location.href;
}

// open_m_Url();

/**
 * 判断当前 链接 是否为 手机版 跳转 手机页面
 * @param c_url
 */
// function open_m_Url(type) {
//     var url = window.location.host + '/dzkm';     // www.310game.com/dzkm
//     // 是否包含手机版标识
//     if(navigator.userAgent.match(/(iPhone|iPod|Android|ios|iPad)/i))
//     {
//         window.location.href = "http://" + url + '/m';
//     }
// }

/**
 * 当屏幕发生变化后
 */
window.addEventListener("orientationchange", function() {
    location.reload();
}, false);