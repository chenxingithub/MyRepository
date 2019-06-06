function drag(_x,_y,dragNode) {
    let node = dragNode;
    let x = 0,y = 0,l = 0,t = 0;
    let isDown = false;
    let html = document.getElementsByTagName('html')[0]
    let cllientH = html.clientHeight;
    let cllientW= html.clientWidth;
    let nodeH = node.offsetHeight;
    let nodeW = node.offsetWidth;

    //按下事件
    node.ontouchstart = function(e) {
        //获取x坐标和y坐标
        e = e.changedTouches[0];
        x = e.clientX;
        y = e.clientY;
        //获取左部和顶部的偏移量
        l = node.offsetLeft;
        t = node.offsetTop;
        //开关打开
        isDown = true;
        //设置样式
        node.style.cursor = 'move';
            // e.preventDefault();
    }
    //移动
    node.ontouchmove = function(e) {
        if (isDown == false) { return false; }
        e.preventDefault();
        e = e.changedTouches[0];
        if(_y) {
            let ny = e.clientY;
            let nt = ny - (y - t);
            nt = (nt+nodeH) <= cllientH ? nt : cllientH - nodeH;
            nt = nt <= 0 ? 0 : nt;
            node.style.top = nt + 'px';
        }
        if(_x) {
            let nx = e.clientX;
            let nl = nx - (x - l);
            nl = (nl+nodeW) <= cllientW ? nl : cllientW - nodeW;
            nl = nl <= 0 ? 0 : nl;
            node.style.left = nl + 'px';
        }
    }
    //抬起事件
    node.ontouchend = function() {
        isDown = false;//开关关闭
        node.style.cursor = 'default';
    }
}

function scrollToTop() {
    
    let scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
    document.body.style.marginTop = -scrollTop + 'px';
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    document.body.style.transition = 'all 1s ease-in-out';
    document.body.style.marginTop = 0;
    
    setTimeout(function () {
        document.body.style.transition = 'none';
    }, 900);
}
// 客户端存储 优先localStorage后cookie
function storeToLocal(name, value, hour) {
   
    if(!name || value == undefined) { return false }
    
    localstore_type();

    if(window._store_support_type == 'LocalStorage') {
        
        window.localStorage.setItem(name, escape(JSON.stringify(value)));

    } else if(window._store_support_type == 'Cookie'){

        saveCookie(name, value, hour);

    } else {
        console.log('您的浏览器禁用了cookie！');
    }
}
//本地存储 取数据
function getLocalStore(name) {
    if(!name) { return false; }

    localstore_type();

    if(window._store_support_type == 'LocalStorage') {
        let data = window.localStorage.getItem(name);
        if(!data) {
            return data;
        }
        return JSON.parse(unescape(data));

    } else if(window._store_support_type == 'Cookie') {

        return getCookie(name);
    } else {
        console.log('您的浏览器禁用了cookie！');
    }
}
function localstore_type() {
    if(!window.hasOwnProperty('_store_support_type')) {
        if(checkLocalStorageSupport()) {
            window._store_support_type = 'LocalStorage';
        } else if(checkCookieSupport()) {
            window._store_support_type = 'Cookie';
        } else {
            window._store_support_type = false;
        }
    }
}
//检查客户端是否支持 LocalStorag
function checkLocalStorageSupport() {
    let mod = 'test';
    try {
        localStorage.setItem(mod, mod);
        localStorage.removeItem(mod);
        return true;
    } catch(e) {
        return false;
    }
}
//检查客户端是否支持 cookie
function checkCookieSupport() {
    let isSupport = false;
    if(typeof(navigator.cookieEnabled) != 'undefined') {
        isSupport = navigator.cookieEnabled;
    } else {   
        document.cookie = 'test';
        isSupport = document.cookie == 'test';
        document.cookie = '';
    }
    
    return (!isSupport ? false : true);
}
// cookie 存储
function saveCookie(name, value, overTime) {
    let time = '';

    if(overTime) {
        let exp = new Date();
        time = exp.setTime(exp.getTime() + hour * 60 * 1000).toGMTString();
        time = ";expires=" + time;
    }
    values = escape(JSON.stringify(value));
    document.cookie = name + "="+ values + time + ";path=/";
}
// cookie 取数据
function getCookie(name){
    let arr,reg=new RegExp("(^| )" + name + "=([^;]*)(;|$)"),data;

    if(arr=document.cookie.match(reg)){
        if(!arr[2]) {
            return arr[2];
        }
        data = JSON.parse(unescape(arr[2]));
        return data;   
    } else {
        return null;
    }
}
//判断 android ios
function judgeClientOs() {
    let u = navigator.userAgent; 
    let isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端 
    let isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    if(isiOS) {
        return 'iOS';
    } else if(isAndroid){
        return 'Android';
    } else { return '' }
}

//匹配{::} 关键字 /\([^\)]*\)/g
function matchKeyWord(str) {
    let re = /(\{:)([^\{:]*)(\:})/g;

    str = str.replace(re, function(par1,par2,par3) {

        return '<span class="bn-tag-keyword">' + par3 + '</span>';
    });

    return str;
}
//手机号码校验
function checkPhoneFormat(phone) {
    if (phone == '') { return -1; }
    else if (phone.length < 11) return -2;
    if(!(/^1[34578]\d{9}$/.test(phone))) return 0;
    return 1;
}
//短信验证码校验
function checkCodeFormat(code) {
    if (code == '') { return -1; }
    else if (code.length != 6) return -2;
    if(!/^[0-9]+$/.test(code)) return 0; 
    return 1;
}
function checkEmailFormat(email) {
    let reg = /^\w+\@+[0-9a-zA-Z]+\.(com|com.cn|edu|hk|cn|net)$/;
    if(email == '') { return -1; }
    else if (!reg.test(email)) return 0;
    return 1;

}
function isWeiXin() {
    let ua = navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == "micromessenger") {
        return true;
    } else {
        return false;
    }
}
/**
 * [GetQueryString 获取在当前页面的url id值]
 * @param {[String]} name [传入要在URL中获取的名称（‘id’）]
 */
function GetQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}
function throttle(method , duration ,delay, _this ){
    var timer = null, 
        // 记录下开始执行函数的时间
        begin = new Date();

    return function(){
        var context = _this, 
            args = arguments, 
            // 记录下当前时间
            current = new Date();
        // 函数节流里的思路
        clearTimeout(timer);

        // 记录下的两个时间相减再与duration进行比较
        if(current-begin >= duration){
             method.apply(context , args);
             begin = current;
        }else{  
            timer = setTimeout(function(){
                method.apply(context , args);
            } , delay);
        }
    }
}
function debounce(fn, delay, _this) {
    // 维护一个 timer
    let timer = null;
  
    return function() {
      // 通过 ‘this’ 和 ‘arguments’ 获取函数的作用域和变量
      let context = _this;
      let args = arguments;
  
      clearTimeout(timer);
      timer = setTimeout(function() {
        fn.apply(context, args);
      }, delay);
    }
}
/*
    时间戳
    xxxx-xx-xx xx:xx:xx （ios兼容问题）
    转 xxxx/xx/xx xx:xx:xx
*/
function tiemFormat(date) {


    if((date+'').indexOf('-') > 0) {
        return date.replace(/\-/g, '/');
    } else if ((date+'').indexOf('/') > 0) {
        return date;
    } else {
        if(!isNaN(date) && isNaN(Date.parse(date))) {
            date = new Date(date);
            
            return date.toLocaleString().replace(/[^\u0000-\u00FF]/g, '');
        }

        return false;
    }
    
}

export {
    drag,
    scrollToTop,
    storeToLocal,
    getLocalStore,
    judgeClientOs,
    matchKeyWord,
    checkPhoneFormat,
    checkCodeFormat,
    checkEmailFormat,
    isWeiXin,
    GetQueryString,
    throttle,
    debounce,
    tiemFormat
};