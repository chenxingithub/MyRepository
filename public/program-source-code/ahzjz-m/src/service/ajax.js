import axios from 'axios';
import { SSL_OP_NO_SESSION_RESUMPTION_ON_RENEGOTIATION } from 'constants';
import { constants } from 'os';

const domain = 'https://www.310game.com';

const baseInfoUrl = domain + '/yh-cms-api/focus';
const focusPictureUrl = domain + '/yh-cms-api/game-info';
const articleContentUrl = domain + '/yh-cms-api/artisan-single/';
const articleListUrl = domain + '/yh-cms-api/artisan-info';
const articleChildrenListUrl = domain + '/yh-cms-api/artisan-type-children/';
const wxAuth_tokenUrl = domain + '/yh-cms-api/wechat-login';
const signin_day_giftUrl = domain + '/yh-cms-api/day-sign-gift';
const signin_day_recordUrl = domain + '/yh-cms-api/day-sign-in-record';
const signin_dayUrl = domain + '/yh-cms-api/day-sign-in';
const signin_week_giftUrl = domain + '/yh-cms-api/week-sign-gift';
const signin_weekUrl = domain + '/yh-cms-api/week-sign-in';
const gift_code_listUrl = domain + '/yh-cms-api/cdk-type-list';
const get_gift_codeUrl = domain + '/yh-cms-api/cdk-list-receive/';
const articleListFuzzy_searchUrl = domain + '/yh-cms-api/artisan-list';
const openServerUrl = domain + '/yh-cms-api/open-service';


axios.interceptors.request.use(//加时间戳防止缓存
    config => {
        
        if(config.method=='post'){
            config.data = {
                ...config.data,
                _t: Date.parse(new Date())/1000,
            }
        }else if(config.method=='get'){
            config.params = {
                _t: Date.parse(new Date())/1000,
                ...config.params
            }
        }
        return config
    },function(error){
        return Promise.reject(error)
    }
);

axios.interceptors.response.use(function (response) {
    // 对响应数据做点什么
    // console.log(response);
    return response;
}, function (error) {
// 对响应错误做点什么
    return Promise.reject(error);
});
/**
 * 根据后台焦点图类别获取焦点图信息，多个类别用英文逗号隔开（'1,2,3'）
 * @param {String} focus_type 
 */
const request_focus_picture = (focus_type) => {
    return axios.get(baseInfoUrl, {
        params: {
            game_id: 13,
            position: focus_type,
            terminal: 2
        }
    });
}
/**
 * 根据游戏标识获取游戏基本信息
 */
const request_base_info = () => {
    return axios.get(focusPictureUrl,{
        params: {
            idfa: 'ahzjz'
        }
    });
}
/**
 * 根据文章id获取文章
 * @param {Number} id 
 */
const request_article = (id) => {
    return axios.get(articleContentUrl + id);
}

/**
 * 根据分类id获取文章列表信息,多个分类id是用英文逗号隔开(1,2,3)
 * @param {String} id 
 * @param {Number} page 
 * @param {Number} limit 
 */
const request_articleList = (id,page,limit) => {
    return axios.get(articleListUrl,{
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
    return axios.get(articleChildrenListUrl + id);
}
//礼包分类列表接口
const gift_code_list = (page,limit) => {
    return axios.get(gift_code_listUrl,{
        params: {
            game_id: 13,
            page: page,
            limit: limit
        }
    });
}
//微信授权获取token
const wxAuth_token = (code) => {
    return axios.get(wxAuth_tokenUrl, {
        params: {
            code: code
        }
    });
}
//日签到奖品列表
const signin_day_gift = (token) => {
    return axios.get(signin_day_giftUrl, {
        params: {
            token: token,
            game_id: 13
        }
    });
}
//日签到记录
const signin_day_record = (token) => {
    return axios.get(signin_day_recordUrl, {
        params: {
            token: token,
            game_id: 13
        }
    });
}
//日签到
const signin_day = (token) => {
    return axios.post(signin_dayUrl, {
        token: token,
        game_id: 13
    });
}
//周签到奖品
const signin_week_gift = (token) => {
    return axios.get(signin_week_giftUrl, {
        params: {
            game_id: 13,
            token: token
        }
    });
}
//周签到
const signin_week = (token) => {
    return axios.post(signin_weekUrl,{
        token: token,
        game_id: 13

    });
}
//礼包码领取接口
const get_gift_code = (id) => {
    return  axios.get(get_gift_codeUrl+id);
}
//文章列表模糊查询
const articleListFuzzy_search = (keyword, page, limit) => {
    return axios.get(articleListFuzzy_searchUrl, {
        params: {
            game_id: 13,
            keyword: keyword,
            terminal: 2,
            page: page,
            limit: limit
        }
    });
}
//获取开服时间
const open_server_list = (type) => {
    return axios.get(openServerUrl, {
        params: {
            game_id: 13,
            type: type
        }
    });
}

export {
    domain,
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