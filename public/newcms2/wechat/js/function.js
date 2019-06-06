function ucGID(fid) {
    try {
        return document.getElementById(fid);
    } catch (e) {
        return false;
    }

}
//判断邮箱格式是否正确
function isEmail(str) {
    var myReg = /^[-_A-Za-z0-9]+@([_A-Za-z0-9]+\.)+[A-Za-z0-9]{2,3}$/;
    if (myReg.test(str))
        return true;
    return false;
}
//判断是否是汉字、字母、数字组成 
function isChinaOrNumbOrLett(s) {
    var regu = /^[\w\u4e00-\u9fa5]+$/;
    var re = new RegExp(regu);
    if (re.test(s)) {
        return true;
    } else {
        return false;
    }
}

function isNumberOr_Letter(s) {
    var regu = /^[0-9a-zA-Z\_\@\.]+$/;
    var re = new RegExp(regu);
    if (re.test(s)) {
        return true;
    } else {
        return false;
    }
}
//产生一个从Min到Max的数字
function getRandomNum(Min,Max){   
    var Range = Max - Min;   
    var Rand = Math.random();   
    return(Min + Math.round(Rand * Range));   
}  
//产生指定位数的随机码
function generateMixed(n) {
    var res = "";
    var chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

    for (var i = 0; i < n; i++) {
        var id = Math.ceil(Math.random() * 35);
        res += chars[id];
    }
    return res;
}
//判断一个值是否存在数组中
function in_array(search,array){
    for(var i in array){
        if(array[i]==search){
            return true;
        }
    }
    
    return false;
}
//获取当前时间戳
function getNowTimeStamp(){
    return new Date().getTime();
}
//写cookie
function setCookie(cookieName, cookieValue, seconds) {
    var expires = new Date();
    expires.setTime(expires.getTime() + parseInt(seconds)*1000);
    document.cookie = encodeURI(cookieName) + '=' + encodeURI(cookieValue) + (seconds ? ('; expires=' + expires.toGMTString()) : "") + '; path=/; domain=appgame.com;';
}
//获取cookie
function getCookie(cname) {
    var cookie_start = document.cookie.indexOf(cname);
    var cookie_end = document.cookie.indexOf(";", cookie_start);
    return cookie_start == -1 ? '' : decodeURI(document.cookie.substring(cookie_start + cname.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}
//删除指定/全部cookie(2014.12.10)
function deleteCookie(cname) {
    var exp = new Date();
        exp.setTime (exp.getTime() - 1);
    var cval = 0;

    if(!cname){
        var keys=document.cookie.match(/[^ =;]+(?=\=)/g);
        if(keys){
            for (var i = keys.length; i--;){
                document.cookie=keys[i]+ '=' + cval + '; expires=' + exp.toGMTString();
            }
        }
    }else{
        document.cookie = cname + "=" + cval + "; expires=" + exp.toGMTString();
    }
}
//获取url参数值
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null){
        return unescape(r[2]);
    }else{
        return null;
    }   
}
//英文、数字、符号均为一个字节,汉字为两个
function strLength(s) {
    var l = 0;
    var a = s.split("");
    for (var i=0;i<a.length;i++) {
        if (a[i].charCodeAt(0)<299) {
            l++;
        } else {
            l+=2;
        }
    }
    return l;
}
//判断是否手机端访问(1,是 0,不是 2,ipad端)
function isMobile(){
    if(/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))){
        if(window.location.href.indexOf("?mobile")<0){
            try{
                if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){
                    return 1;
                }else if(/iPad/i.test(navigator.userAgent)){
                    return 2;
                }else{
                    return 0;
                }
            }catch(e){}
        }
    }
}
//判断PC或者移动设备
function isPcOrMobile(){
    var sUserAgent = navigator.userAgent.toLowerCase();
    var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
    var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
    var bIsMidp = sUserAgent.match(/midp/i) == "midp";
    var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
    var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
    var bIsAndroid = sUserAgent.match(/android/i) == "android";
    var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
    var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";

    if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
        return true;
    } else {
        return false;
    }
}
//判断是否使用微信浏览器
function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}