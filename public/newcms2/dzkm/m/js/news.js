/**
 * Created by Administrator on 2017/2/22.
 */

window.onload = function () {
}

/**
 * 当屏幕发生变化后
 */
window.addEventListener("orientationchange", function() {
    location.reload();
}, false);

var number = 0;

/**
 * 新闻资讯 类型 按钮点击
 */
function news(name) {
    var type_ul = document.getElementsByClassName("list_type")[0];
    var list_type = type_ul.children;

    var type_ul = document.getElementsByClassName("list_text");

    var news_text = document.getElementsByClassName("news_text")[0];

    

    var type_list = new Array("dzkm_zx","dzkm_xw","dzkm_gg","dzkm_hd","dzkm_gl");

    for (var i = 0; i < type_list.length; i++){
        if(name == type_list[i])
        {
            list_type[i].children[0].className = "no";
        }
    }

    // // 循环创建 多个函数 对应多个点击事件
    // for (var i = 0; i < list_type.length; i++) {
    //     (function(i){
    //         list_type[i].onclick = function () {
    //             // news_text.style.left = "-" + i + "00%";

    //             list_type[number].children[0].className = "";

    //             number = i;
    //             list_type[i].children[0].className = "no";
    //             setHeight(number);
    //         }
    //     })(i);
    //     // 自己调用自己 传对应位置的值到 函数
    // }
}

function setHeight(number) {
    var news_text = document.getElementsByClassName("news_text")[0];

    var news_ul = news_text.children;

    var height = news_ul[number].offsetHeight;

    news_text.style.height = height + "px";
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

function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}

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
            sdelay= setTimeout('returnTop()',15);
        }
    }else
    {
        window.scrollBy(0,-200);//Only for y vertical-axis
        if(document.body.scrollTop > 0) {
            sdelay= setTimeout('returnTop()',15);
        }
    }
}

// 修改 移动端 文章 文字 样式
function setSingleStyle() {
    // 先 获取 当前 文章 所有标签
    var text = document.getElementsByClassName("info_content")[0].innerHTML;
    // 替换 font-size 里的 值 为 0.3rem;
    text = text.replace(/(font-size:.*?;)/ig,"font-size: 0.3rem;");
    // 重新填入 文章标签
    document.getElementsByClassName("info_content")[0].innerHTML = text;

    var textList = document.getElementsByClassName("info_content")[0].children;

    for (var i = 0; i < textList.length; i++) {
        var img = textList[i].getElementsByTagName("img");
        
        if(img.length > 0)
        {
            // 文章里的 img 标签 去掉 缩进 
            textList[i].className = textList[i].className + " no";
        }

        var p = textList[i];
        var style = p.getAttribute("style");

        if(style == 'text-align: center;' || style == 'text-align:center;' || style == 'text-align: center' || style == 'text-align:center')
        {
            // p标签 是 居中 状态的 话 去掉缩进
            if(textList[i].className == ' no' || textList[i].className == 'no'){}
            else{ textList[i].className = textList[i].className + " no"; }
        }

        // 手机端 文字大小 以 rem 为单位 px 转 rem
        // 搜索 文字大小 属性 转 rem
        // var span = textList[i].getElementsByTagName("span");
        // if(span.length > 0)
        // {
        //     var s_style = textList[i].children[0].getAttribute("style");

        //     if(s_style !=null && s_style.indexOf(';'))
        //     {
        //         var size = '0px';
        //         var new_size = '0rem';
        //         var value = s_style.split(';');

        //         for (var j = 0; j < value.length; j ++) {
        //             if(value[j].indexOf('font-size') > -1)
        //             {
        //                 size = value[j].split(':')[1].trim(); 

        //                 new_size = '0.3rem';
        //             }
        //         };

        //         var new_value = s_style.replace(size,new_size);

        //         textList[i].children[0].style = new_value;
        //     }
        // }
    }
}