;(function(win, lib) {
    var doc = win.document;
    var docEl = doc.documentElement;
    var metaEl = doc.querySelector('meta[name="viewport"]');
    var flexibleEl = doc.querySelector('meta[name="flexible"]');
    var dpr = 0;
    var scale = 0;
    var tid;
    var flexible = lib.flexible || (lib.flexible = {});
    
    if (metaEl) {
        // console.warn('将根据已有的meta标签来设置缩放比例');
        var match = metaEl.getAttribute('content').match(/initial\-scale=([\d\.]+)/);
        if (match) {
            scale = parseFloat(match[1]);
            dpr = parseInt(1 / scale);
        }
    } else if (flexibleEl) {
        var content = flexibleEl.getAttribute('content');
        if (content) {
            var initialDpr = content.match(/initial\-dpr=([\d\.]+)/);
            var maximumDpr = content.match(/maximum\-dpr=([\d\.]+)/);
            if (initialDpr) {
                dpr = parseFloat(initialDpr[1]);
                scale = parseFloat((1 / dpr).toFixed(2));    
            }
            if (maximumDpr) {
                dpr = parseFloat(maximumDpr[1]);
                scale = parseFloat((1 / dpr).toFixed(2));    
            }
        }
    }

    if (!dpr && !scale) {
        var isAndroid = win.navigator.appVersion.match(/android/gi);
        var isIPhone = win.navigator.appVersion.match(/iphone/gi);
        var devicePixelRatio = win.devicePixelRatio;
        if (isIPhone) {
            // iOS下，对于2和3的屏，用2倍的方案，其余的用1倍方案
            if (devicePixelRatio >= 3 && (!dpr || dpr >= 3)) {                
                dpr = 3;
            } else if (devicePixelRatio >= 2 && (!dpr || dpr >= 2)){
                dpr = 2;
            } else {
                dpr = 1;
            }
        } else {
            // 其他设备下，仍旧使用1倍的方案
            dpr = 1;
        }
        scale = 1 / dpr;
    }

    docEl.setAttribute('data-dpr', dpr);
    if (!metaEl) {
        metaEl = doc.createElement('meta');
        metaEl.setAttribute('name', 'viewport');
        metaEl.setAttribute('content', 'initial-scale=' + scale + ', maximum-scale=' + scale + ', minimum-scale=' + scale + ', user-scalable=no');
        if (docEl.firstElementChild) {
            docEl.firstElementChild.appendChild(metaEl);
        } else {
            var wrap = doc.createElement('div');
            wrap.appendChild(metaEl);
            doc.write(wrap.innerHTML);
        }
    }

    function refreshRem(adapt_width){
        var width = docEl.getBoundingClientRect().width;
        
        // if (width / dpr > 750) {
        //     width = 750 * dpr;
        // }
        var rem = width / 10;
        docEl.style.fontSize = rem + 'px';
        flexible.rem = win.rem = rem;
    }

    win.addEventListener('resize', function() {
        clearTimeout(tid);
        tid = setTimeout(refreshRem, 300);
    }, false);
    win.addEventListener('pageshow', function(e) {
        if (e.persisted) {
            clearTimeout(tid);
            tid = setTimeout(refreshRem, 300);
        }
    }, false);

    if (doc.readyState === 'complete') {
        doc.body.style.fontSize = 12 * dpr + 'px';
    } else {
        doc.addEventListener('DOMContentLoaded', function(e) {
            doc.body.style.fontSize = 12 * dpr + 'px';
        }, false);
    }
    

    refreshRem();

    flexible.dpr = win.dpr = dpr;
    flexible.refreshRem = refreshRem;
    flexible.rem2px = function(d) {
        var val = parseFloat(d) * this.rem;
        if (typeof d === 'string' && d.match(/rem$/)) {
            val += 'px';
        }
        return val;
    }
    flexible.px2rem = function(d) {
        var val = parseFloat(d) / this.rem;
        if (typeof d === 'string' && d.match(/px$/)) {
            val += 'rem';
        }
        return val;
    }


})(window, window['lib'] || (window['lib'] = {}));





// // 全局字体rem
// function init (size){
//     // 获取初始的fontsize,16为比例标准
//     var originalSize = parseInt((window.getComputedStyle(document.documentElement,null)).fontSize);

//     function fn(){
//         var win_w = parseInt(document.body.clientWidth);
//         /* 对应页面最大设计尺寸大宽度设置size */
//         //win_w = (win_w>size)?size:win_w;
//         var win_h = parseInt(document.body.clientHeight),
//             html = document.getElementsByTagName('html')[0],
//             zoom=(win_w / size) / (originalSize/16) * 100;
//         html.style.fontSize = zoom + 'px';
//     };

//     // 设置size
//     fn();

//     //横竖屏检测
//     function detectOtt() {
//         try {
//             if (window.orientation == 90 || window.orientation == -90) {
//                 document.getElementById('horizon').style.display = 'block';
//             } else if (window.orientation == 0 || window.orientation == 180) {
//                 document.getElementById('horizon').style.display = 'none';
//             }
//         }catch(error) {
//         }
//     }
//     window.onresize = function(){
//         detectOtt();
//     };
// };


    /**
        * 当屏幕发生变化后
    */
// window.addEventListener("orientationchange", function() {
//     location.reload();
// }, false);

// init(640);

// window.addEventListener("onorientationchange" in window ? "orientationchange" : "resize", function() {  
//     if (window.orientation === 180 || window.orientation === 0) {   
//        //alert('竖屏状态！');
//        return false;
//     }   
//     if (window.orientation === 90 || window.orientation === -90 ){   
//         //alert('横屏状态！'); 
//         refreshRem(750); 
//     }    
// }, false);