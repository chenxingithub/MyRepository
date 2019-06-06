$(function() {
    var navInfo = {
        id: 25,
        parentId: 25,
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
            return this.idMapToNav[this.parentId];
        },
        
    };
    navInfo.id = GetQueryString('id');

    

    //基本配置
    function setBaseInfo(data) { //设置基本配置
        var data = data.data[0];
        var downloadbox_m = $('.downloadbox_m');

        downloadbox_m.find('img').attr('src', imgDomain + data.android_dow_code_img);
        
        var iosUrl = data.ios_download_url ? data.ios_download_url : "JavaScript:alert('暂无ios版');";
        var androidUrl = data.android_download_url ? data.android_download_url : "JavaScript:alert('暂无android版');";

        downloadbox_m.find('.ios_btn').attr('href', iosUrl);
        downloadbox_m.find('.android_btn').attr('href', androidUrl);

        $('.logo').attr('src', imgDomain + data.logo_url);
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


    //文章内容
    function getTextParam() {//ajax 参数
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-single/' + navInfo.id,
            data: null,
        }
        return param;
    }
    function getText(data) {
        var data = data.data;
        var title = data.title;
        var time = data.updated_at;
        navInfo.parentId = data.artisan_type_id;
        var textNode = $('.text');
        $('title').text(data.title);
        textNode.find('.textTitle').text(data.title);
        textNode.find('.time').text(formatTime1(data.updated_at));
        textNode.find('.author').text(data.sketch);
        textNode.find('.maintext').html(data.content);
        
        //所在位置
        $('#pageRange').text(navInfo.getNav());
        
    }
    send(getTextParam(),getText);//ajax

});