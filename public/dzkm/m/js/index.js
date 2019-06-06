/**
 * Created by Administrator on 2017/2/20.
 */

// open_pc_Url();

window.onload = function () {
    news();
    
    init_swiper2();
}

/**
 * 判断当前 链接 是否为 手机版 跳转 手机页面
 * @param c_url
 */
// function open_pc_Url() {
//     var url = window.location.host + '/newcms2/dzkm';     // www.310game.com/dzkm
//     var search = window.location.search;
//     // 是否包含手机版标识
//     if(navigator.userAgent.match(/(iPhone|iPod|Android|ios|iPad)/i))
//     {

//     }else
//     {
//         window.location.href = "http://" + url + '/index.html' + search;
//     }
// }

var number = 0;

/**
 * 新闻资讯 类型 按钮点击
 */
function news() {
    var type_ul = document.getElementsByClassName("list_type")[0];
    var list_type = type_ul.children;

    var type_ul = document.getElementsByClassName("list_text");

    var news_text = document.getElementsByClassName("news_text")[0];

    // 循环创建 多个函数 对应多个点击事件
    for (var i = 0; i < list_type.length; i++) {
        (function(i){
            list_type[i].onclick = function () {
                news_text.style.left = "-" + i + "00%";

                list_type[number].children[0].className = "";

                number = i;
                list_type[i].children[0].className = "no";
            }
        })(i);
        // 自己调用自己 传对应位置的值到 函数
    }
}

function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}

/**
 * 一键下载
 */
function m_download() {
    if(navigator.userAgent.match(/(iPhone|iPod|ios|iPad)/i))
    {
        if(i_url == null || i_url == "")
        {
            alert("暂无 IOS 版本");
        }else
        {
            location.href = i_url;
        }
    }else {
        if(a_url == null || a_url == "")
        {
            alert("暂无 Android 版本");
        }else
        {
            if(isWeiXin())
            {
                if(a_url.indexOf('.apk') > 0)
                {
                    var time = (new Date()).getTime();
                    time = time.toString(16);
                    a_url = a_url + (a_url.indexOf('?') > 0 ? '&' : '?') + 'click_uuid=' + time;
                }
            }
            location.href = a_url;
        }
    }
}

var a_url = "";
var i_url = "";

function setDownUrl(a_link , i_link , a_wx_link , i_wx_link) {
    a_url = a_link;
    i_url = i_link;
    if(a_wx_link !== null && a_wx_link !== "" && a_wx_link !== undefined)
    {
        // 如果存在微信链接 优先使用
        a_url = a_wx_link;
    }
    if(i_wx_link !== null && i_wx_link !== "" && i_wx_link !== undefined)
    {
        // 如果存在微信链接 优先使用
        i_url = i_wx_link;
    }
}

/**
 * 一键加群
 */
function addQQ(ios , android) {
    if(navigator.userAgent.match(/(iPhone|iPod|ios|iPad)/i))
    {
        window.open(ios);
    }else {
        window.open(android)
    }
}

function openWX() {
    var popBg = document.getElementsByClassName("popBg")[0];
    var codePop = document.getElementsByClassName("codePop")[0];

    popBg.style.display = "block";
    codePop.style.display = "block";
}

/**
 * 微信窗口 关闭按钮
 */
function closeWX() {
    var popBg = document.getElementsByClassName("popBg")[0];
    var codePop = document.getElementsByClassName("codePop")[0];
    
    popBg.style.display = "none";
    codePop.style.display = "none";
}

/**
 * 初始化 图片轮播
 */
function init_swiper1() {
    // 先获取轮播图 容器
    var swiper = new Swiper('.swiper-container1', {
        // 获取轮播图 导航点
        pagination: '.swiper-pagination',
        // 一页显示个数
        slidesPerView: 1,
        // 导航点是否可以点击
        paginationClickable: true,
        // 图片之前的 间隔
        spaceBetween: 0,
        // 循环
        loop: true,
        // 自动播放 时间
        autoplay: 2500,
        // 滑动后 是否停止 自动播放
        autoplayDisableOnInteraction: false
    });
}

/**
 * 初始化 图片轮播
 */
function init_swiper2() {
    // 先获取轮播图 容器
    var swiper = new Swiper('.swiper-container', {
        // 获取轮播图 导航点
        // pagination: '.swiper-pagination',
        // 一页显示个数
        slidesPerView: 1.5,
        // 导航点是否可以点击
        paginationClickable: true,
        // 图片之前的 间隔
        spaceBetween: 15,
        // // 循环
        // loop: true,
        // // 自动播放 时间
        // autoplay: 2500,
        // // 滑动后 是否停止 自动播放
        // autoplayDisableOnInteraction: false
    });
}

/**
 * 当屏幕发生变化后
 */
window.addEventListener("orientationchange", function() {
    location.reload();
}, false);

// 全局字体rem
function init (size){
    // 获取初始的fontsize,16为比例标准
    var originalSize = parseInt((window.getComputedStyle(document.documentElement,null)).fontSize);

    function fn(){
        var win_w = parseInt(document.body.clientWidth);
        /* 对应页面最大设计尺寸大宽度设置size */
        //win_w = (win_w>size)?size:win_w;
        var win_h = parseInt(document.body.clientHeight),
            html = document.getElementsByTagName('html')[0],
            zoom=(win_w / size) / (originalSize/16) * 100;
        html.style.fontSize = zoom + 'px';
    };

    // 设置size
    fn();

    //横竖屏检测
    function detectOtt() {
        if (window.orientation == 90 || window.orientation == -90) {
            document.getElementById('horizon').style.display = 'block';
        } else if (window.orientation == 0 || window.orientation == 180) {
            document.getElementById('horizon').style.display = 'none';
        }
    }
    window.onresize = function(){
        detectOtt();
    };
};

/**
 * 滚动 到 顶部
 * @type {number}
 */
var sdelay = 0;
function returnTop() {
    // window.scrollBy(0,-80);//Only for y vertical-axis
    // if(document.body.scrollTop > 0) {
    //     sdelay= setTimeout('returnTop()',15);
    // }

    var height = document.body.scrollHeight;
    // alert(height);

    if(height <= 1200)
    {
        window.scrollBy(0,-10);//Only for y vertical-axis
        if(document.body.scrollTop > 0) {
            sdelay= setTimeout('returnTop()',15);
        }
    }else if(height <= 2000)
    {
        window.scrollBy(0,-60);//Only for y vertical-axis
        if(document.body.scrollTop > 0) {
            sdelay= setTimeout('returnTop()',15);
        }
    }else if(height <= 3000)
    {
        window.scrollBy(0,-70);//Only for y vertical-axis
        if(document.body.scrollTop > 0) {
            sdelay= setTimeout('returnTop()',15);
        }
    }else if(height <= 5000){
        window.scrollBy(0,-100);//Only for y vertical-axis
        if(document.body.scrollTop > 0) {
            sdelay= setTimeout('returnTop()',30);
        }
    }else
    {
        window.scrollBy(0,-200);//Only for y vertical-axis
        if(document.body.scrollTop > 0) {
            sdelay= setTimeout('returnTop()',30);
        }
    }
}