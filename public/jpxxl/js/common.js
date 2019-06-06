var targetName = "Authorization";
// var urlPrefix = 'http://devwww.310game.com'; //测试
// var urlPrefix = 'http://www.310game.com';//正式
// var imageUrl = 'http://www.310game.com/';//正式
// var iconUrl = 'http://www.310game.com/';//正式
var urlPrefix = ''; //上线
var imageUrl = 'https://cdn3.ibingniao.com/';//上线
var iconUrl = 'https://cdn3.ibingniao.com/';//上线

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
 * jsonp
 * @param {*} url 
 * @param {*} type 
 * @param {*} data 
 * @param {*} token 
 * @param {*} fn 
 */
function send(param, fn) {
    $.ajax({
		type: param.type,
		async: true,
        url: param.url,
        data: param.data,
        dataType:"json" ,
		success: fn,
		error:function(){
			alert("网络错误"); 
		} 
	});
}


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
        return  unescape(r[2]);
    }
    return null;
}

 //扫一扫 Modal
function scanModal() {
    var attentionWX = $('#attentionWX');
	var erweima = $('#erweima');
	attentionWX.on('click',function() {
		erweima.show();
	});
	erweima.on('click',function() {
		erweima.hide();
	});
}
//分享 Modal
function shareModal() {
    var shareWX = $('#shareWX');
	var shareModal = $('#shareModal');
	shareWX.on('click',function() {
		shareModal.show();
	});
	shareModal.on('click',function() {
		shareModal.hide()
    });
}
//电脑版未开放提示 modal
function pcNoneModal() {
    var pcTipModal = $('#pcTipModal');
    var versionPc = $('#versionPc');
    versionPc.on('click',function() {
        pcTipModal.show();
    });
    pcTipModal.on('click',function() {
        pcTipModal.hide();
    });
}

 //底部导航栏的图标切换
 function BottomNavChange() {
    var navs = $('.navBar .navIcon');
    var ulFather = $('.navBar ul');
    var links = ulFather.find('li a');
    ulFather.on('click',function(event) {
        var target = event.target;
        navs.forEach(function(element) {
            var backgroundPositionY = $(element).css('backgroundPositionY');
            backgroundPositionY == '-.1733rem' ? '' : $(element).css('backgroundPositionY','-.1733rem');
        }, this);
        $(target).css('backgroundPositionY','-1.2795rem');
    });
 }

//官网按钮点击切换
function homePageBtnfunc() {
    var homePageBtn = $('#topBtn .homePageBtn');
    homePageBtn.on('click',function() {
		$(this).css('backgroundPosition','-1.72rem 0');
    });
}
//顶部下载按钮切换
function downloadBtnfunc() {
    var downloadBtn1 = $('#topBtn .gemeDownloadBtn');
    downloadBtn1.on('click',function() {
		$(this).css('backgroundPosition','-1.72rem 0');
    });
}
//判断是否来自微信公众号入口
function WeChatEntry() {
    
    if(GetQueryString('from') == 'wx') {
        return true;
    } else {
        return false;
    }
}
//判断是否为微信浏览器
function isWeiXin() {
    let ua = navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == "micromessenger") {
        return true;
    } else {
        return false;
    }
}

//微信配置
function wxConf(suc_fn) {
    
    var params = {
        type: 'get',
        url: 'https://www.310game.com/yh-cms-api/wechat-share-config',
        data: {
            url: window.location.href
        }
    };
    send(params, suc_fn);

}
function getWxConf() {

	wxConf(function(res) {
 
		wx.config({
			beta: res.beta,
			debug: res.debug,
			appId: res.appId,
			timestamp:res.timestamp,
			nonceStr: res.nonceStr,
			signature: res.signature,
			jsApiList: res.jsApiList,
			url: res.url
		});
	
		wx.ready(function(){

			wx.onMenuShareTimeline({
				title: '【九品小县令】',
				link: 'http://www.310game.com/jpxxl/index.html',
				imgUrl: 'https://www.310game.com/jpxxl/images/icon.png',
				success: function () {
					
				},
				cancel: function () {}
			});
	
			wx.onMenuShareAppMessage({
				title: '【九品小县令】',
				desc: '体验官场的百味生活',
				link: 'http://www.310game.com/jpxxl/index.html',
				imgUrl: 'https://www.310game.com/jpxxl/images/icon.png',
				type: 'link',
				dataUrl: '',
				success: function () {
					
				},
				cancel: function () {},
			});
		});
	});
}

if(isWeiXin()) {
    getWxConf();
}