import axios from 'axios'
// import Qs from 'qs'//防止跨域时 option请求

// create an axios instance
const service = axios.create({
    baseURL: 'https://www.310game.com', // api的base_url
    timeout: 5000 // request timeout
})
//request header的Accept-Language属性 当值为zh-HK、zh-TW时返回繁体中文，en-US返回英文
service.defaults.headers.post['Accept-Language'] = 'en-US';
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

service.interceptors.request.use(
    config => {
        if(config.method == 'post') {
            config.data = { ...config.data, _t: Date.now()/1000 }
        } else if(config.method == 'get'){
            config.params = {
                ...config.params,
                _t: Date.now()/1000
            }
        }
        return config
    }, function(error) {
        return Promise.reject(error)
    }
)

// respone interceptor
service.interceptors.response.use(
    response => response,

    error => {
      console.log('err' + error)// for debug

      return Promise.reject(error)
    })

  export default service
