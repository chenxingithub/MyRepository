import service from '@/utils/request.js'

const gameId = 18;
const gameIdfa = 'yhphs';

const baseInfoUrl = '/yh-cms-api/focus';
const focusPictureUrl = '/yh-cms-api/game-info';
const articleContentUrl = '/yh-cms-api/artisan-single/';
const articleListUrl = '/yh-cms-api/artisan-info';
const articleChildrenListUrl = '/yh-cms-api/artisan-type-children/';
const wxAuth_tokenUrl = '/yh-cms-api/wechat-login';
const signin_day_giftUrl = '/yh-cms-api/day-sign-gift';
const signin_day_recordUrl = '/yh-cms-api/day-sign-in-record';
const signin_dayUrl = '/yh-cms-api/day-sign-in';
const signin_week_giftUrl = '/yh-cms-api/week-sign-gift';
const signin_weekUrl = '/yh-cms-api/week-sign-in';
const gift_code_listUrl = '/yh-cms-api/cdk-type-list';
const get_gift_codeUrl = '/yh-cms-api/cdk-list-receive/';
const articleListFuzzy_searchUrl = '/yh-cms-api/artisan-list';
const openServerUrl = '/yh-cms-api/open-service';


/**
 * 根据后台焦点图类别获取焦点图信息，多个类别用英文逗号隔开（'1,2,3'）
 * @param {String} focus_type
 */
const request_focus_picture = (focus_type) => {
  return service({
    url: baseInfoUrl,
    method: 'get',
    params: {
      game_id: gameId,
      position: focus_type,
      terminal: 2
    }
  });
}
/**
 * 根据游戏标识获取游戏基本信息
 */
const request_base_info = () => {
  return service({
    url: focusPictureUrl,
    method: 'get',
    params: {
      idfa: gameIdfa
    }
  });
}
/**
 * 根据文章id获取文章
 * @param {Number} id
 */
const request_article = (id) => {
    // return axios.get(articleContentUrl + id);
  return service({
    url: articleContentUrl + id,
    method: 'get',
  });
}

/**
 * 根据分类id获取文章列表信息,多个分类id是用英文逗号隔开(1,2,3)
 * @param {String} id
 * @param {Number} page
 * @param {Number} limit
 */
const request_articleList = (id,page,limit) => {
  return service({
    url: articleListUrl,
    method: 'get',
    params: {
      artisan_type_id: id,
      terminal: 2,
      page: page,
      limit: limit
    }
  });
}
/**
 * 根据类别id查询文章分类子分类
 * @param {Number} id
 */
const request_articleChildrenList = (id) => {
  return service({
    url: articleChildrenListUrl + id,
    method: 'get'
  });
}
//礼包分类列表接口
const gift_code_list = (page,limit) => {
  return service({
    url: gift_code_listUrl,
    method: 'get',
    params: {
      game_id: gameId,
      page: page,
      limit: limit
    }
  });
}
//微信授权获取token
const wxAuth_token = (code) => {
  return service({
    url: wxAuth_tokenUrl,
    method: 'get',
    params: {
      code: code
    }
  });
}
//日签到奖品列表
const signin_day_gift = (token) => {
  return service({
    url: signin_day_giftUrl,
    method: 'get',
    params: {
      token: token,
      game_id: gameId
    }
  });
}
//日签到记录
const signin_day_record = (token) => {
  return service({
    url: signin_day_recordUrl,
    method: 'get',
    params: {
      token: token,
      game_id: gameId
    }
  });
}
//日签到
const signin_day = (token) => {
  return service({
    url: signin_dayUrl,
    method: 'post',
    data: {
      token: token,
      game_id: gameId
    }
  });
}
//周签到奖品
const signin_week_gift = (token) => {
  return service({
    url: signin_week_giftUrl,
    method: 'get',
    params: {
      game_id: gameId,
      token: token
    }
  });
}
//周签到
const signin_week = (token) => {
  return service({
    url: signin_weekUrl,
    method: 'get',
    params: {
      token: token,
      game_id: gameId
    }
  });
}
//礼包码领取接口
const get_gift_code = (id) => {
  return service({
    url: get_gift_codeUrl + id,
    method: 'get'
  });
}
//文章列表模糊查询
const articleListFuzzy_search = (keyword, page, limit) => {
  return service({
    url: articleListFuzzy_searchUrl,
    method: 'get',
    params: {
      game_id: gameId,
      keyword: keyword,
      terminal: 2,
      page: page,
      limit: limit
    }
  });
}
//获取开服时间
const open_server_list = (type) => {
  return service({
    url: openServerUrl,
    method: 'get',
    params: {
      game_id: gameId,
      type: type
    }
  });
}

export {
    request_focus_picture,
    request_base_info,
    request_article,
    request_articleList,
    request_articleChildrenList,
    wxAuth_token,
    signin_day_gift,
    signin_day_record,
    signin_day,
    signin_week_gift,
    signin_week,
    gift_code_list,
    get_gift_code,
    articleListFuzzy_search,
    open_server_list
}
