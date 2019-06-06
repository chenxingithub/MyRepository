import axios from 'axios'

// create an axios instance
const service = axios.create({
    baseURL: 'https://www.310game.com', // api的base_url
    timeout: 5000 // request timeout
})

service.interceptors.request.use(
    config => {
        if(config.method == 'post') {
            config.data = {
                ...config.data,
                _t: Date.now()/1000
            }
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
      console.log(error.response.data);
      return Promise.reject(error)
    })

  export default service
