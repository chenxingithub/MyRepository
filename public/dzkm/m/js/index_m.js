$(function() {

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


    //焦点图
    function focusPictureParam() {
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/focus',
            data: {
                game_id: 9,
                position: '1,2',
                terminal: 2,
            }
        }
        return param;
    }
    function setFocusPicture(data) {
        //data = {1: Array(2), 2: Array(5)}
        var data = data.data;
        var focus_pic1 = $('.focus_pic1');
        var focus_pic2 = $('.focus_pic2');
        var imageUrl = '';

        $.each(data[1], function(index, item) {

            $(focus_pic1[index]).attr('src', imgDomain + item.picture);
            $(focus_pic1[index]).parent('a').attr('href', './article.html?id='+ item.id);
        });

        $.each(data[2], function(index, item) {

            $(focus_pic2[index]).attr('src', imgDomain + item.picture);
        });
        init_swiper1();//初始化swiper 对象 from ./index.js
    }
    send(focusPictureParam(),setFocusPicture);//ajax



    //文章列表

    function getlistParam() {
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-info',
            data: {
                artisan_type_id: '25,26,27,28,29',
                terminal: 1,
                page: 1,
                limit: 5,
            }
        }
        return param;
    }
    function setList(data) {
        var nav = {
            '25': '最新',
            '26': '新闻',
            '27': '公告',
            '28': '活动',
            '29': '攻略',
            '30': '新闻',
            '31': '攻略',
        }
        var listText = '';
        $.each(data, function(index, item) {
            var data = item.data[0].data;
            var text = '';

            for(var i = 0; i < data.length; i++) {
                // log(data.updated_at);
                text += '<li><p><span class="tips">'+ nav[data[i].artisan_type_id] +
                '</span><span class="time">'+ formatTime1(data[i].updated_at) +
                '</span></p><a href="./article.html?id='+ data[i].id +
                '" target="_blank">'+ data[i].title +'</a></li>';

            }
            listText += '<ul class="list_text">'+ text +'</ul>'
        });

        $('.news_text').append(listText);
    }

    send(getlistParam(),setList);//ajax
});