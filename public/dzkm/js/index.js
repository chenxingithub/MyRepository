$(function() {

    
    //基本配置
    function setBaseInfo(data) { //设置基本配置
        var data = data.data[0];
        
        var download_box = $('.download_box');
        $('#kf_phone').text(data.service_phone);
        $('#kf_qq').text(data.service_qq);
        download_box.find('img').attr('src', imgDomain + data.android_dow_code_img);

        var iosUrl = data.ios_download_url ? data.ios_download_url : "JavaScript:alert('暂无ios版');";
        var androidUrl = data.android_download_url ? data.android_download_url : "JavaScript:alert('暂无android版');";

        download_box.find('.ios_btn').attr('href', iosUrl);
        download_box.find('.android_btn').attr('href', androidUrl);

        $('.gameIcon').attr('src', imgDomain + data.logo_url);
        // log(data);
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


    //游戏列表
    function setGameList(res) {
        var data = res.data.data;
        // log(data[0]);
        var gameList = '';
        $.each(data, function(index, item) {
            gameList += '<li style="display: block;"><img src="'+ imgDomain +item.logo_url +
            '" width="20px" height="20px"><a href="'+ item.official_url +
            '" target="_blank">'+ item.game_name +'</a></li>';
        });
        // $('#game_list ul').append(gameList);
        // log(gameList);
    }
    function gameList() {
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/game-list',
            data: {
                page: 1,
                limit: 4,
            }
        }
        return param;
    }
    send(gameList(),setGameList);//ajax
    
    //底部文章板块1
    function setPanel_1(data) {//设置面板1链接
        var data = data.data;
        // log(data);
        var panel_1 = $('.panel_1');
        panel_1.attr('href', './article.html?id=' + data.id);
        // panel_1.find('img').attr('src', urlPrefix + data.icon);
    }
    function getParam_panel1() {//ajax 参数
        var textId = 125;
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-single/' + textId,
            data: null,
        }
        return param;
    }
    send(getParam_panel1(),setPanel_1);//ajax

    
    //底部文章板块2
    function setPanel_2(data) {//设置面板2链接
        var data = data.data;
        // log(data);
        var panel_2 = $('.panel_2');
        panel_2.attr('href', './article.html?id=' + data.id);
        // panel_2.find('img').attr('src', urlPrefix + data.icon);
    }
    function getParam_panel2() {//ajax 参数
        var textId = 126;
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-single/' + textId,
            data: null,
        }
        return param;
    }
    send(getParam_panel2(),setPanel_2);//ajax
    

    //底部列表板块 3 + 顶部游戏列表
   
    /**
     * 生成列表
     * @param {Array} data 
     */
    function createList(data) {
        var text = '';
        for(var i = 0; i < data.length; i++) {
            var time = formatTime(data[i].updated_at);
            text += '<li><span>'+ time +'</span><a href="./article.html?id='+
            data[i].id + '" target="_blank">' + data[i].title + '</a></li>';
        }
        return text;
    }
    function setPanel_3(data) { //生成面板3 文章列表 *头部游戏列表
     
        var textIds = ['25','26','27','28','29'];
        var gameListId = 24;
        var text_content = '';
        for(var i = 0; i < textIds.length ; i++) {
            text_content += "<ul style='display: " +
            (i === 0 ? 'block' : 'none') + "'>"+
            createList(data[textIds[i]].data[0].data) +"</ul>"
        }

        $('#info').append(text_content);

        //游戏列表
        var gameLists = data[gameListId].data[0].data;
        var gameList = '';
        $.each(gameLists, function(index, item) {
            gameList += '<li style="display: block;"><img src="'+ imgDomain + item.icon +
            '" width="20px" height="20px"><a href="'+ item.jump_url +
            '" target="_blank">'+ item.title +'</a></li>';
        });
        $('#game_list ul').append(gameList);
  
    }
    function getParam_panel3() {//ajax 参数
        var textId = 25;
        var param = {
            type: 'get',
            url: urlPrefix + 'yh-cms-api/artisan-info',
            data: {
                artisan_type_id: '24,25,26,27,28,29',
                terminal: 1,
                page: 1,
                limit: 5,
            },
        }
        return param;
    }
    send(getParam_panel3(),setPanel_3);//ajax


});