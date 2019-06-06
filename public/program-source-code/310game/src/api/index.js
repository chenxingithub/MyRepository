import service from '@/utils/request.js'
import axios from 'axios'
import md5 from 'js-md5'

window.md5 = md5;

//https://www.icebirdgame.com
//https://www.310game.com
const domain = 'https://www.310game.com';
const auth_domain = 'https://m.1aiyouxi.com';

const baseInfoUrl = domain + '/yh-cms-api/game-info';
const focusPictureUrl = domain + '/yh-cms-api/focus';
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
const req_registerUrl = domain + '/user?action=register';

//310授权
const cmsAuthUrl = domain + '/yh-cms-api/user/login';
//冰鸟授权 /yh-cms-api/user/login
const bnAuthUrl = auth_domain + '/oauth/token';//统一授权登录
const bnUserInfoUrl = auth_domain + '/oauth/userInfo';//冰鸟统一授权获取用户信息
const regUrl = auth_domain + '/account/userReg';//注册
const logUrl = auth_domain + '/account/authorize';//登录
const sendCodeUrl = auth_domain + '/account/sendCode';//发送验证码
const userInfoUrl = auth_domain + '/account/info';//获取用户信息
const modfPwdUrl = auth_domain + '/account/userChgPwd';//直接修改密码
const bindTelUrl = auth_domain + '/account/opBind';//绑定和解绑手机号码接口
const authNameUrl = auth_domain + '/account/bindReal'; //实名认证接口
const modifyPwdUrl = auth_domain + '/account/telChgPwd'; //根据绑定的手机来修改密码

const app_key = 'cd110d3743bdadd28157e63f221e524e';
const app_id = 110000000;

const createSign = (obj, pay_sign) => {//签名
  var newKey = Object.keys(obj).sort().reverse();
  var valueString = '';//存放排好序的键值对
  for (var i = 0; i < newKey.length; i++) {
    valueString += newKey[i] + '^' + obj[newKey[i]];
  }

  // valueString = md5(valueString + md5(pay_sign));
  valueString = md5(valueString + pay_sign);

    return valueString;

};
/**
 * 根据后台焦点图类别获取焦点图信息，多个类别用英文逗号隔开（'1,2,3'）
 * @param {String} focus_type
 */
const request_focus_picture = (focus_type) => {
    return service({
      url: focusPictureUrl,
      method: 'get',
      params: {
        game_id: 17,
        position: focus_type,
        terminal: 1
      }
    });
};
/**
 * 根据游戏标识获取游戏基本信息
 */
const request_base_info = () => {
    return service({
        url: baseInfoUrl,
        method: 'get',
        params: {
            idfa: '310game'
        }
    });
};
/**
 * 根据文章id获取文章
 * @param {Number} id
 */
const request_article = (id) => {
    return service({
      url: articleContentUrl + id,
      method: 'get',
    });
};

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
        terminal: 1,//pc
        page: page,
        limit: limit
      }
    });
};
/**
 * 根据类别id查询文章分类子分类
 * @param {Number} id
 */
const request_articleChildrenList = (id) => {
    return service({
      url: articleChildrenListUrl + id,
      method: 'get'
    });
};
//礼包分类列表接口
const gift_code_list = (page,limit) => {
    return service({
      url: 'gift_code_listUrl',
      method: 'get',
      params: {
        game_id: 17,
        page: page,
        limit: limit
      }
    });
};
//微信授权获取token
const wxAuth_token = (code) => {
    return axios.get(wxAuth_tokenUrl, {
        params: {
            code: code
        }
    });
};
//日签到奖品列表
const signin_day_gift = (token) => {
    return axios.get(signin_day_giftUrl, {
        params: {
            token: token,
            game_id: 17
        }
    });
};
//日签到记录
const signin_day_record = (token) => {
    return axios.get(signin_day_recordUrl, {
        params: {
            token: token,
            game_id: 17
        }
    });
};
//日签到
const signin_day = (token) => {
    return axios.post(signin_dayUrl, {
        token: token,
        game_id: 17
    });
};
//周签到奖品
const signin_week_gift = (token) => {
    return axios.get(signin_week_giftUrl, {
        params: {
            game_id: 17,
            token: token
        }
    });
};
//周签到
const signin_week = (token) => {
    return axios.post(signin_weekUrl,{
        token: token,
        game_id: 17

    });
};
//礼包码领取接口
const get_gift_code = (id) => {
    return  axios.get(get_gift_codeUrl+id);
};
//文章列表模糊查询
const articleListFuzzy_search = (keyword, page, limit) => {
    return axios.get(articleListFuzzy_searchUrl, {
        params: {
            game_id: 17,
            keyword: keyword,
            terminal: 2,
            page: page,
            limit: limit
        }
    });
};
//获取开服时间
const open_server_list = (type) => {
    return axios.get(openServerUrl, {
        params: {
            game_id: 17,
            type: type
        }
    });
};


//注册 https://www.icebirdgame.com/user?action=register
const req_register = (accou, pwd, tel, tel_code, name, id_num) => {
  let params = {
    reg_type: 3,
    app_id: app_id,
    user_name: accou,
    password: md5(pwd + md5(pwd)),
    tel_num: tel,
    smscode: tel_code,
    name: name,
    id: id_num,
    time: Date.now(),
    os: "ios",
    from_h5: 1
  };
  params['sign'] = createSign(params, app_key);
  // return axios.post(regUrl, params);

  return service({
    url: regUrl,
    method: 'post',
    data: {
      ...params
    }
  });
};
//发送短信
const req_sendCode = (tel,type) => {
  let params = {
    app_id: app_id,
    tel_num: tel,
    type: type,
    time: Date.now(),
    from_h5: 1,
    os: "ios"
  };

  params['sign'] = createSign(params, app_key);

  return service({
    url: sendCodeUrl,
    method: 'post',
    data: { ...params }
  });
};

const req_userInfo = (account, token) => {
  let params = {
    user_name: account,
    access_token: token,
    time: Date.now(),
    app_id: app_id,
    os: 'ios',
    from_h5: 1
  };
  params['sign'] = createSign(params, app_key);

  return service({
    url: userInfoUrl,
    method: 'post',
    data: { ...params }
  });
};

const req_login = (name, pwd) => {//登录
  let params = {
    login_type: 6,
    user_name: name,
    password: md5(pwd + md5(pwd)),
    time: Date.now(),
    os: 'ios',
    app_id: app_id,
    from_h5: 1
  };

  params['sign'] = createSign(params, app_key);

  return service({
    url: logUrl,
    method: 'post',
    data: { ...params }
  });
};

const req_modfPwd = (account, cur_pwd, new_pwd, token) => {//修改密码

  let params = {
    user_name: account,
    cur_password: md5(cur_pwd + md5(cur_pwd)),
    new_password: md5(new_pwd + md5(new_pwd)),
    access_token: token,
    time: Date.now(),
    os: 'ios',
    app_id: app_id,
    from_h5: 1
  };
  params['sign'] = createSign(params, app_key);
  return service({
    url: modfPwdUrl,
    method: 'post',
    data: { ...params }
  });
};

//解绑绑定手机
const req_bindTel = (tel, account, code, token, op_type) => {
  let params = {
    tel_num: tel,
    user_name: account,
    code: code,
    access_token: token,
    op_type: op_type,
    time: Date.now(),
    os: 'ios',
    app_id: app_id,
    from_h5: 1
  };
  params['sign'] = createSign(params, app_key);
  return service({
    url: bindTelUrl,
    method: 'post',
    data: { ...params }
  });
};
//实名认证
const req_authName = (token, name, id) => {
  let params = {
    name: name,
    id: id,
    access_token: token,
    time: Date.now(),
    os: 'ios',
    app_id: app_id,
    from_h5: 1
  };
  params['sign'] = createSign(params, app_key);
  return service({
    url: authNameUrl,
    method: 'post',
    data: { ...params }
  });
};

//根据手机号码修改密码
const req_modifyPwd = (tel,code,pwd) => {
  let params = {
    tel_num: tel,
    code: code,
    password: md5(pwd + md5(pwd)),
    time: Date.now(),
    os: 'ios',
    app_id: app_id,
    from_h5: 1
  };
  params['sign'] = createSign(params, app_key);
  return service({
    url: modifyPwdUrl,
    method: 'post',
    data: { ...params }
  });
};

//统一授权token登录
const req_bnAuthUrl = (code, redirect_url, app_id) => {
  let params = {
    code: code,
    redirect_url: redirect_url,
    app_id: app_id,
    from_h5: 1,
  };
  params['sign'] = createSign(params, app_key);

  return service({
    url: bnAuthUrl,
    method: 'post',
    data: { ...params }
  });
};
//统一授权获取用户信息
const req_bnUserInfoUrl = (token) => {
  let params = {
    access_token: token,
    app_id: app_id,
    from_h5: 1
  };
  params['sign'] = createSign(params, app_key);

  return service({
    url: bnUserInfoUrl,
    method: 'post',
    data: { ...params }
  });
};
//cms 统一登录
const req_cmsAuth = (token) => {

  return service({
    url:  cmsAuthUrl,
    method: 'post',
    data: {
      access_token: token
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
  open_server_list,
  req_register,
  req_sendCode,
  req_userInfo,
  req_login,
  req_modfPwd,
  req_bindTel,
  req_authName,
  req_modifyPwd,
  auth_310,
  req_bnAuthUrl,
  req_bnUserInfoUrl,
  req_cmsAuth
}
