$(function() {

    var navInfo = {
        id: 25,
        page: 1,
        limit: 15,
        last_page: 2,
        per_page: 15,
        total: 7,
        showMaxPageBtn: 4,
        idMapToNav: {
            '25': '最新',
            '26': '新闻',
            '27': '公告',
            '28': '活动',
            '29': '攻略',
            '30': '新闻',
            '31': '攻略',
        },
        getNav: function() {
            return this.idMapToNav[this.id];
        },
        
    };


    //基本配置
    function setBaseInfo(data) { //设置基本配置
        var data = data.data[0];
        $('.logo').attr('src', imgDomain + data.logo_url);
        // setDownUrl(a_link , i_link , a_wx_link , i_wx_link);
        setDownUrl(data.android_download_url , data.ios_download_url , data.android_download_url , data.ios_download_url);
    }
    function getParam() { //ajax 参数
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/game-info',
            data: {
                idfa: 'dzkm',
            }
        }
        return param;
    }
    send(getParam(),setBaseInfo);//ajax

    //文章列表
    function getlistParam() {
        var id = navInfo.id;
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-info',
            data: {
                artisan_type_id: 25,
                terminal: 1,
                page: 1,
                limit: 15,
            }
        }
        return param;
    }
    function setList(data) {
        var listText = '';
        // log(data);
        $.each(data, function(index, item) {
            var data = item.data[0].data;
            var text = '';

            for(var i = 0; i < data.length; i++) {
                // log(data.updated_at);
                text += '<li><p><span class="tips">'+ navInfo.idMapToNav[data[i].artisan_type_id] +
                '</span><span class="time">'+ formatTime1(data[i].updated_at) +
                '</span></p><a href="./article.html?id='+ data[i].id +
                '" target="_blank">'+ data[i].title +'</a></li>';

            }
            listText += '<ul class="list_text">'+ text +'</ul>'
        });

        $('.news_text').append(listText);
    }

    // send(getlistParam(),setList);//ajax

    //下拉加载

    //防抖动函数
    function debounce(func, wait, immediate) {
        // 定时器变量
        var timeout;
        return function() {
        // 每次触发 scroll handler 时先清除定时器
        clearTimeout(timeout);
        // 指定 xx ms 后触发真正想进行的操作 handler
        timeout = setTimeout(func, wait);
        };
    };
    
    function beginLoad(node) {//判断是否符合加载条件
        
        var screenHeight = $('html')[0].clientHeight;
        flagPosition = node.getBoundingClientRect();

        return  flagPosition.top < screenHeight ? true : false;

    }
    function sendCallback(data) {
        var listText = '';
        $.each(data, function(index, item) {
            var data = item.data[0].data;
            var text = '';

            for(var i = 0; i < data.length; i++) {
                // log(data.updated_at);
                text += '<li><p><span class="tips">'+ navInfo.idMapToNav[data[i].artisan_type_id] +
                '</span><span class="time">'+ formatTime1(data[i].updated_at) +
                '</span></p><a href="./article.html?id='+ data[i].id +
                '" target="_blank">'+ data[i].title +'</a></li>';

            }
            listText += text;
        });
        var res = data[navInfo.id].data[0];
        navInfo.last_page = res.last_page;
        $('.list_text').append(listText);

        if(navInfo.page >= navInfo.last_page) {
            $('.page_navi').text('已加载全部');
        }
        navInfo.page += 1;//加载完成 page 加一
    }
    var flag = $('#flag');
    // 实际想绑定在 scroll 事件上的 handler
    function realFunc(){
       
        if(navInfo.page > navInfo.last_page) {
            return false;
        }
        if(beginLoad(flag[0])) {
            
            send(getlistParam(), sendCallback);
        }

    }
    function getlistParam() {
        var listParam = {
            url: urlPrefix+'yh-cms-api/artisan-info',
            type: 'get',
            data: {
                page: navInfo.page,
                limit: navInfo.limit,
                game_id: 9,
                terminal: 2,
                artisan_type_id: navInfo.id,
            }
        };
        return listParam;
    }
    send(getlistParam(), sendCallback);//首次加载
    // 防抖动
    $(window).on('scroll', debounce(realFunc, 250));


    $('.list_type').on('click',function(e) {
        var target = $(e.target);
        if(target.hasClass('no')) {
            return false;
        } else {
            $('.no').removeClass('no');
            target.addClass('no');
            navInfo.id = target.data('id');
            navInfo.page = 1;//
            send(getlistParam(), sendCallback);//导航栏切换加载
            $('.page_navi').text('下拉加载更多');
            $('.list_text').children().remove();
        }
    });
   
});