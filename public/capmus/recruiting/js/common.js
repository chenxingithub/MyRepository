var urlPrefix = 'https://www.310game.com/';//正式
// var log = console.log.bind(console);
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
        cache:false,
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

var util = {
    tipModal: function(type,msg) {//依赖了图标
        $('#tipModal').remove();
        clearTimeout(timeOut);

        var tip_html = '<div id="tipModal" class="box-com">'
                        +'<div class="box-f flex-m">'
                            +'<div class="tip-content flex-list">'
                                +'<img src="./images/'+ (type == 'fail' ? 'hud_failed.png' : 'hud_success.png') +'" alt="image">'
                                +'<p>'+ msg +'</p>'
                            +'</div>'
                        +'</div>'
                    +'</div>';
        $('body').append(tip_html);

        var timeOut= setTimeout(function(){
            $('#tipModal').remove();
        },850);
    },
    loadingStart: function (param) {
        var html = '<div id="loading" class="box-com">'
                    +'<div class="box-f">'
                            +'<div class="bouncing-loader">'
                                +'<div></div>'
                                +'<div></div>'
                                +'<div></div>'
                                +'<div></div>'
                            +'</div>'
                            +'<p style="color: #fff;font-size:18px;">'+ '正在发送...' +'</p>'
                    +'</div>'
                +'</div>';
        $('body').append(html);
    },
    loadingEnd: function() {
        $('#loading').remove();
    }

}

// util.loadingStart();