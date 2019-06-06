import axios from 'axios';
let domain = 'https://www.310game.com';


const baseInfo_url = domain + '/yh-cms-api/spirit-configure';
const panelList_url = domain + '/yh-cms-api/spirit-plate';
const ruleComponent_url = domain + '/yh-cms-api/spirit-plate-assembly';
const keyWord_url = domain + '/yh-cms-api/spirt-keyword';
const feedbackSub_url = domain + '/yh-cms-api/spirt-feedback';
const feedbackReplyQuery_url = domain + '/yh-cms-api/spirt-feedback-reply';
const feedbackMsgStatus_url = domain + '/yh-cms-api/spirt-feedback-read';

axios.defaults.baseURL = 'https://www.310game.com';

axios.interceptors.request.use( //加时间戳防止缓存
  config => {

    if (config.method == 'post') {
      config.data = {
        ...config.data,
        _t: Date.parse(new Date()) / 1000,
      }
    } else if (config.method == 'get') {
      config.params = {
        _t: Date.parse(new Date()) / 1000,
        ...config.params
      }
    }
    return config
  },
  function (error) {
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



//基础信息请求
const baseInfoReq = (gameId, channel_id) => {

  return axios.get(baseInfo_url, {
    params: {
      game_id: gameId,
      channel: channel_id
    }
  });
}
//板块列表
const panelListReq = (gameId) => {

  return axios.get(panelList_url, {
    params: {
      game_id: gameId
    }
  })
}
//规则组件
const ruleComponentReq = (id) => {
  return axios.get(ruleComponent_url, {
    params: {
      spirit_plate_id: id
    }
  });
}
//关键字
const keywordReq = (keyword, type, urlParams) => {
  return axios.get(keyWord_url, {
    params: {
      game_id: urlParams.game_id,
      keyword: keyword,
      type: type,
      from: urlParams.role_id,
      user_id: urlParams.user_id,
      role_name: urlParams.role_name,
      vip: urlParams.vip,
      role_rank: urlParams.role_rank,
      service_id: urlParams.service_id
    }
  });
}
//反馈回复查询
const feedbackReplyQueryReq = (urlParams) => {

  return axios.get(feedbackReplyQuery_url, {
    params: {
      game_id: urlParams.game_id,
      to: urlParams.role_id,
      limit: 50,
      page: 1
    }
  });
}
//反馈提交
const feedbackSubReq = (suggest, urlParams) => {
  return axios.post(feedbackSub_url, {
    game_id: urlParams.game_id,
    user_id: urlParams.user_id,
    from: urlParams.role_id,
    role_name: urlParams.role_name,
    vip: urlParams.vip,
    role_rank: urlParams.role_rank,
    feedback_message: suggest,
    service_id: urlParams.service_id
  });
}

//feedback新消息状态查询
const feedbackMsgStatusQuery = (role_id) => {
  return axios.get(feedbackMsgStatus_url, {
    params: {
      from_id: role_id
    }
  });
}
//feedback新消息状态山上报
const feedbackMsgStatusReported = (role_id) => {
  return axios.post(feedbackMsgStatus_url, {
    from_id: role_id
  });
}

export {
  baseInfoReq,
  panelListReq,
  ruleComponentReq,
  keywordReq,
  feedbackReplyQueryReq,
  feedbackSubReq,
  feedbackMsgStatusQuery,
  feedbackMsgStatusReported
}
