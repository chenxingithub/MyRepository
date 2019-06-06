var log = console.log.bind(console);
// var urlPrefix = 'http://devwww.310game.com'; //测试
var urlPrefix = 'https://www.310game.com';//正式
var imageUrl = 'https://cdn3.ibingniao.com/';//正式
// var urlPrefix = ''; //上线


var URL = {
    baseInfo: urlPrefix + '/yh-cms-api/spirit-configure',
    panelList: urlPrefix + '/yh-cms-api/spirit-plate',
    ruleComponent: urlPrefix + '/yh-cms-api/spirit-plate-assembly',
    keyWord: urlPrefix + '/yh-cms-api/spirt-keyword',
    feedbackSub: urlPrefix + '/yh-cms-api/spirt-feedback',
    feedbackReplyQuery: urlPrefix + '/yh-cms-api/spirt-feedback-reply'
};

/**
 * 
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
        cache: false,
        data: param.data,
		success: fn,
		error:function(res){
            log(res);
            if(res.responseText) {
                alert(res.status+' '+ JSON.parse(res.responseText).message);
            } else {
                alert(res.status+' '+ res.statusText);
            }

		}
	});
}
//基础信息请求
function baseInfoReq(data, callbackFN) { 
    var params = {
        type: 'GET',
        url: URL.baseInfo,
        data: data
    };
    send(params, callbackFN);
}
//板块列表
function panelListReq(data, callbackFN) {
    var params = {
        type: 'GET',
        url: URL.panelList,
        data: data
    };
    send(params, callbackFN);
}
//规则组件
function ruleComponentReq(data, callbackFN) {
    var params = {
        type: 'GET',
        url: URL.ruleComponent,
        data: data
    };
    send(params, callbackFN);
}
//关键字
function keywordReq(data, callbackFN) { 
    var params = {
        type: 'GET',
        url: URL.keyWord,
        data: data
    };
    send(params, callbackFN);
 }
//反馈回复查询
function feedbackReplyQueryReq(data, callbackFN) {
    var params = {
        type: 'GET',
        url: URL.feedbackReplyQuery,
        data: data
    };
    send(params, callbackFN);
}
//反馈提交
function feedbackSubReq(data, callbackFN) {
    var params = {
        type: 'POST',
        url: URL.feedbackSub,
        data: data
    };
    send(params, callbackFN);
 }

//util
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

//匹配{::} 关键字 /\([^\)]*\)/g
function matchKeyWord(str) {
    var re = /(\{:)([^\{:]*)(\:})/g;

    str = str.replace(re, function(par1,par2,par3) {

        return '<span class="bn-keyword">' + par3 + '</span>';
    });

    return str;
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

//加载 依赖的js css ===== JSSDK.start中调用 =================
var dynamicLoading = {
    /**
     * @param {Array} filesPath
     * @param {Function} callFn
     */
    loadFile: function(filesPath,callFn) {

        this.orderLoadFile(filesPath, callFn);
    },
    orderLoadFile: function(filePath, callFn) {

        if(filePath.length <= 0) { return false; }

        var fileType = '';
        var path = filePath.shift();
        fileType = path.split('.').pop();

        dynamicLoading[fileType](path, function() {

            if(!filePath.length) { (typeof(callFn) == 'function') && callFn(); }

            dynamicLoading.orderLoadFile(filePath,callFn);
        });
        
    },
    css: function(path, loadedFn){
        if(!path || path.length === 0){
            throw new Error('argument "path" is required !');
        }
        var func = loadedFn || '';
        var head = document.getElementsByTagName('head')[0];
        var link = document.createElement('link');
        link.href = path;
        link.rel = 'stylesheet';
        link.type = 'text/css';
        head.appendChild(link);
        link.onload = link.onreadystatechange = function () {
            if (!this.readyState || 'loaded' === this.readyState || 'complete' === this.readyState) {

                if(func) { func(); }
             }
        }
    },
    js: function(path, loadedFn){
        if(!path || path.length === 0){
            throw new Error('argument "path" is required !');
        }
        var func = loadedFn || '';
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.src = path;
        script.type = 'text/javascript';
        head.appendChild(script);
        script.onload = script.onreadystatechange = function () {
            if (!this.readyState || 'loaded' === this.readyState || 'complete' === this.readyState) {
                
               if(func) { func(); }
            }
        }
    }
}
//加载 依赖的js css ===== JSSDK.start中调用 =================
var bn_utils = {
    tipModal: function(type,msg) {//依赖的图标/css
        $('#bn-tipModal').remove();
        clearTimeout(timeOut);

        var tip_html = '<div id="bn-tipModal" class="bn-box-com">'
                        +'<div class="bn-box-f bn-flex-m">'
                            +'<div class="bn-tip-content bn-flex-list">'
                                +'<img src="./images/jlyx/'+ (type == 'fail' ? 'hud_failed.png' : 'hud_success.png') +'" alt="image">'
                                +'<p>'+ msg +'</p>'
                            +'</div>'
                        +'</div>'
                    +'</div>';
        $('body').append(tip_html);

        var timeOut= setTimeout(function(){
            $('#bn-tipModal').remove();
        }, 600);
    },
    loadingStart: function (tipMsg) {
        var html = '<div id="loading" class="bn-box-com">'
                    +'<div class="bn-box-f">'
                            +'<div class="bouncing-loader">'
                                +'<div></div>'
                                +'<div></div>'
                                +'<div></div>'
                                +'<div></div>'
                            +'</div>'
                            +'<p style="color: #fff;font-size:18px; text-align: center;">'+ (tipMsg ? tipMsg : '正在发送...') +'</p>'
                    +'</div>'
                +'</div>';
        $('body').append(html);
    },
    loadingEnd: function() {
        $('#loading').remove();
    },
    dynamicLoading: dynamicLoading,
    /**
     * 获取location.search的参数值
     * @param name
     * @returns {string}
     */
    getUrlParam: function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"), r;
        r = window.location.search.substr(1).match(reg);
        return r != null ? decodeURIComponent(r[2]) : '';
    },
    //判断Android ios
    judgeClient: function() {
        var u = navigator.userAgent; 
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端 
        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        if(isiOS) {
            return 'iOS';
        } else{
            return 'Android';
        }
    }
}
