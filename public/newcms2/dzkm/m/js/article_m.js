$(function() {
    var textId = GetQueryString('id');


    // setDownUrl('http://cdn.ibingniao.com/uploads/new_dzkm_310_cms.apk' , 'http://cdn.ibingniao.com/uploads/new_dzkm_310_cms.apk' , 'http://cdn.ibingniao.com/uploads/new_dzkm_310_cms.apk' , 'http://cdn.ibingniao.com/uploads/new_dzkm_310_cms.apk');

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

    //文章内容
    function getTextParam() {//ajax 参数
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-single/' + textId,
            data: null,
        }
        return param;
    }
    function getText(data) {
        var data = data.data;
        var title = data.title;
        var time = data.updated_at;
        // navInfo.parentId = data.artisan_type_id;
        var textNode = $('.container');
        $('title').text(data.title);
        textNode.find('.textTitle').text(data.title);
        textNode.find('.time1').text(formatTime1(data.updated_at));
        textNode.find('.author').text(data.sketch);
        textNode.find('.info_content').html(data.content);
        
        //所在位置
        // log(navInfo.getNav());
        // $('#pageRange').text(navInfo.getNav());
        
    }
    send(getTextParam(),getText);//ajax
});