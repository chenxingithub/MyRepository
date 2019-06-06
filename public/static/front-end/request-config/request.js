var DOMAIN = 'http://www.310game.com/',

var APICONFIG = {
    baseInfo: DOMAIN + 'yh-cms-api/game-info',
    focusImg: DOMAIN + 'yh-cms-api/focus',
    supportArticle: DOMAIN + 'yh-cms-api/game-info',
    articleListQuery: DOMAIN + 'yh-cms-api/artisan-list',
    getArticleInfo: DOMAIN + 'yh-cms-api/artisan-single/',
    getArticleList: DOMAIN + 'yh-cms-api/game-info',
    gameList: DOMAIN + 'yh-cms-api/game-list',
    articleTypeChildren: DOMAIN + 'yh-cms-api/artisan-type-children/',
    giftTypeList: DOMAIN + 'yh-cms-api/cdk-type-list',
    getCdk: DOMAIN + 'yh-cms-api/cdk-list-receive/',
};


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


var BnOwSdk = {
    GAME_ID: "",
    IDFA: "",
    init: function(config) {
        this.GAME_ID = config['game_id'];
    },
    getBaseInfo: function(param) {//官网基础信息接口

      return  $.get(APICONFIG.baseInfo, { idfa: param});

    },
    getFocusImg: function(position,terminal) {//焦点图获取接口
        var param = {
            game_id: this.GAME_ID,
            position: position || '123',
            terminal: terminal || 1,
        }

        return $.get(APICONFIG.focusImg, param );
    },
    getSupportArticle: function(id) {//文章点赞
        var param = {
            id: id,
            action: 'ChangeFabulousNum',
            _method: 'put',
        };

        return $.ajax({url: APICONFIG.supportArticle, type: 'put', data: param});
    },
    getArticleListQuery: function(keyword,terminal,page,limit) {//文章列表模糊查询
        var param = {
            game_id: this.GAME_ID,
            keyword: keyword,
            terminal: terminal || 1,
            page: page || 1,
            limit: limit || 5,
        };

        return $.get(APICONFIG.articleListQuery, param);
    },
    getArticleInfo: function(id) {//根据文章id获取文章信息

        return $.get(APICONFIG.getArticleInfo, { id: id });
    },
    getArticleList: function(id, terminal, page, limit){//根据分类id获取文章列表
        var param = {
            artisan_type_id: id,
            terminal: terminal,
            page: page,
            limit: limit, 
        };

        return $.get(APICONFIG.getArticleList, param);
    },
    getGameList: function(page, limit) {//游戏列表
        var param = {
            page: page,
            limit: limit,
        };

        return $.get(APICONFIG.gameList, param);
    },
    getArticleTypeChildren: function(articleId) {//查询文章分类子分类
        
        return $.get(APICONFIG.articleTypeChildren, { id: articleId });
    },
    getGiftTypeList: function(page, limit) {//礼包分类列表接口
        var param = {
            game_id: this.GAME_ID,
            page: page,
            limit: limit,
        };
        return $.get(APICONFIG.giftTypeList, param);
    },
    getCdk: function(giftId) {//礼包码领取接口
        
        return $.get(APICONFIG.getCdk, { id: giftId, });
    }
};