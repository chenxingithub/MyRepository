var urlPrefix = '/';//正式
var imgDomain = 'https://cdn3.ibingniao.com/';
var log = console.log.bind(console);
/**
 *  异步发送ajax请求 (Promise callback)
 * @param {String} url [url]
 * @param {String} type [请求类型]
 * @param {Object} data [请求数据]
 * @param {String} token [token]
 */
function sendRequest(url, type, data, token) {
    return $.ajax({
        url: url,
        type: type,
        dataType: 'json',
        data: data,
        beforeSend: function(request) {
            request.setRequestHeader(targetName, token);
        }
    });
}

/**
 * 同步发送ajax请求 ( allback)
 * @param {String} url [url]
 * @param {String} type [请求类型]
 * @param {Object} data [请求数据]
 * @param {String} token [token]
 */
function sendAsync(url, type, data, token) {
    var result;
    $.ajax({
        url: url,
        type: type,
        dataType: 'json',
        async: false,
        data: data,
        beforeSend: function(request) {
            request.setRequestHeader(targetName, token);
        },
        success: function(data){
            result = data;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert(XMLHttpRequest, textStatus, errorThrown);
            console.log(XMLHttpRequest);
        }
    });
    return result;
}

/**
 * 
 * @param {Object} param [请求参数]
 * @param {Function} fn [回调]
 */
function send(param, fn) {
    $.ajax({
		type: param.type,
		async: true,
        url: param.url,
        data: param.data,
        dataType:"json",
		success: fn,
		error:function(xhr){
            alert("网络错误 " + xhr.status);
            // log(xhr);
		} 
	});
}
//判断Android ios
function judgeClient() {
    var u = navigator.userAgent; 
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端 
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    if(isiOS) {
        return 'iOS';
    } else{
        return 'Android';
    }
}
/**
 * 获取cookie
 * @param {String} name 
 */
function getCookie(name){
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg)){
        return JSON.parse(unescape(arr[2]));
    } else {
        return null;
    } 
}

/**
 * [GetQueryString 获取在当前页面的url id值]
 * @param {[String]} name [传入要在URL中获取的名称（‘id’）]
 */
function GetQueryString(name) {//获取在当前页面的token值
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    
    if(r!=null) {
        return  unescape(r[2]) - 0;
    }
    return null;
}

 /**
 * "2018-01-22 17:47:44"
 * @param {String} time 
 */
function formatTime(time) {
    var time = time.split(' ');
    var ymds = time[0].split('-');
    var smms = time[1].split(':');
    var year = ymds[0];
    var month = ymds[1];
    var day  = ymds[2];

    return month+'-'+day;
}
 /**
 * "2018-01-22 17:47:44"
 * @param {String} time 
 */
function formatTime1(time) {
    var time = time.split(' ');
    var ymds = time[0].split('-');
    var smms = time[1].split(':');
    var year = ymds[0];
    var month = ymds[1];
    var day  = ymds[2];

    return year +'年'+ month + '月' + day + '日';
}


adapteClient();
/**
 * 判断当前 链接 是否为 手机版 跳转 手机页面
 * @param c_url
 */
function adapteClient() {
    
    // 是否包含手机版标识
    if(navigator.userAgent.match(/(iPhone|iPod|Android|ios|iPad)/i)) {

       if(window.location.href.indexOf('/m') < 0) {
            var parser = document.createElement('a');
            parser.href = location.href;
            var filename = parser.pathname.match(/[A-Za-z0-9_]*.html/);
            filename = filename ? filename[0] : '';
            var path = parser.pathname.replace(filename, '');
            path = path + ( path.substr(path.length-1,1) == '/' ? 'm/' : '/m/' );
            var port = parser.port ? ':'+ parser.port : '';
            var url = parser.protocol + '//' + parser.hostname + port + path + filename + parser.search;
            window.location.href = url;

       } else  {
           return false;
       }
        
    } else {
       if(window.location.href.indexOf('/m') > 0) {
            var href = window.location.href;
            var url = href.replace(/\/m/,'');
            log(url);
            window.location.href = url;
            
       } else {
           return false;
       }
        
    }
}
//=========================================

