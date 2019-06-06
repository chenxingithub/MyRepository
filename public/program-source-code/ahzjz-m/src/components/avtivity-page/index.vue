<template>
    <div class="avtivity-page">
        <Header></Header>
        <div class="head-img"></div>
        <div class="day-sign-in">
            <div class="day-title flex">
                <span class="gift-icon"></span>
                <span>7日签到礼包</span>
            </div>
            <div class="day-list flex-wrap-sb-c">
                <!-- <div class="gift-day act-gift gift-got">
                    <div class="gift-cover">已经领取</div>
                    <div class="gift-border"></div>
                    <div class="gift-content">
                        <img src="../../assets/examp.jpg" alt="images">
                        <p>第一天</p>
                    </div>
                </div>
                <div class="gift-day act-gift">
                    <div class="gift-cover">已经领取</div>
                    <div class="gift-border"></div>
                    <div class="gift-content">
                        <img src="../../assets/examp.jpg" alt="images">
                        <p>第二天</p>
                    </div>
                </div>
                <div class="gift-day act-gift">
                    <div class="gift-cover">已经领取</div>
                    <div class="gift-border"></div>
                    <div class="gift-content">
                        <img src="../../assets/examp.jpg" alt="images">
                        <p>第三天</p>
                    </div>
                </div>
                <div class="gift-day act-gift">
                    <div class="gift-cover">已经领取</div>
                    <div class="gift-border"></div>
                    <div class="gift-content">
                        <img src="../../assets/examp.jpg" alt="images">
                        <p>第四天</p>
                    </div>
                </div>
                <div class="gift-day act-gift">
                    <div class="gift-cover">已经领取</div>
                    <div class="gift-border"></div>
                    <div class="gift-content">
                        <img src="../../assets/examp.jpg" alt="images">
                        <p>第五天</p>
                    </div>
                </div>
                <div class="gift-day act-gift">
                    <div class="gift-cover">已经领取</div>
                    <div class="gift-border"></div>
                    <div class="gift-content">
                        <img src="../../assets/examp.jpg" alt="images">
                        <p>第六天</p>
                    </div>
                </div> -->
                <div :class="{'gift-day': true,'act-gift': true, 'gift-got': index < signinDay}" 
                    v-for="(item, index) in signinDayGiftList" :key="index">
                    <div class="gift-cover">已经领取</div>
                    <div class="gift-border"></div>
                    <div class="gift-content">
                        <img :src="domain + '/' + item.img_url" alt="images">
                        <p>{{ item.title }}</p>
                    </div>
                </div>
                
            </div>
            <a href="javascript:;" @click="clickSigninDay" class="get-day-gift get-gift-btn">领取礼包</a>
        </div>
        <div class="week-sign-in">
            <div class="day-title flex">
                <span class="gift-icon"></span>
                <span>每周签到礼包</span>
            </div>
            <div class="week-list flex-sb-c">
               <div :class="{'gift-day': true ,'act-gift': true, 'gift-got': signinDay >= 8}" 
               v-for="(item, index) in signinWeekGiftList" :key="index">
                    <div class="gift-cover">已经领取</div>
                    <div class="gift-border"></div>
                    <div class="gift-content">
                        <img :src="domain+'/'+item.img_url" alt="image">
                        <p>{{ item.title }}</p>
                    </div>
                </div>
               <!-- <div class="gift-day act-gift">
                    <div class="gift-cover">已经领取</div>
                    <div class="gift-border"></div>
                    <div class="gift-content">
                        <img src="../../assets/examp.jpg" alt="images">
                        <p>第一天</p>
                    </div>
                </div>
               <div class="gift-day act-gift">
                    <div class="gift-cover">已经领取</div>
                    <div class="gift-border"></div>
                    <div class="gift-content">
                        <img src="../../assets/examp.jpg" alt="images">
                        <p>第一天</p>
                    </div>
                </div> -->
            </div>
            <a href="javascript:;" @click="clickSigninWeek" class="get-week-gift get-gift-btn">点击领取</a>
        </div>
        <div class="exchange-code">
            <p>兑换码使用方法</p>
            <div class="exchange-code-content flex-wrap-sb-c">
                <a class="step-img" href="./static/img/howToExchange1.png">
                    <img src="../../assets/howToExchange1.png" alt="image">
               </a>
                <a class="step-img" href="./static/img/howToExchange2.png">
                    <img src="../../assets/howToExchange2.png" alt="image">
               </a>
                <a class="step-img" href="./static/img/howToExchange3.png">
                    <img src="../../assets/howToExchange3.png" alt="image">
               </a>
                <a class="step-img" href="./static/img/howToExchange4.png">
                    <img src="../../assets/howToExchange4.png" alt="image">
               </a>
            </div>
        </div>
        <div class="get-rule">
            <p>领取规则</p>
            <div class="rule-detail">
                <p>1、每周一零点刷新，每日签到即可领取礼包码</p>
                <p>2、每位训练师每天可以领取一次签到礼包</p>
                <p>3、一周签到7次即可领取周礼包</p>
                <p>4、礼包码请在领取当天使用，过期则无效</p>
            </div>
        </div>
        <Footer-nav></Footer-nav>
        <!-- 获取礼包码弹窗 -->
        <get-gift-modal 
        :modalType="modal_type" 
        :signinWeekGiftList="signinWeekGiftList" 
        :signinDayGiftList="signinDayGiftList" 
        :dayGift="dayGift" 
        @closegiftmodal="closeGiftModal"
        ></get-gift-modal>
        <!-- 提示弹窗 -->
        <tip-message :tipMessage="tipMessage" 
            :tipMsg="tipMsg" 
            :tipType="tipType"
            @closemodal="closeModal"></tip-message>
    </div>
</template>
<script>
import Header from '../header/header';
import FooterNav from '../footer/footer';
import getGiftModal from './getTipModal';
import { mapActions, mapState } from 'vuex';
import { GetQueryString, getLocalStore, storeToLocal } from '../../utils/index.js';
import { tipMessage, tiemFormat } from '../../utils/index.js'
import { 
    wxAuth_token,
    signin_day_gift,
    signin_day_record,
    signin_day,
    signin_week_gift,
    signin_week
    } from '../../service/ajax.js';

export default {
    name: 'activityPage',
    components: {
        Header,
        getGiftModal,
        tipMessage,
        FooterNav
    },
    data() {
        return {
            domain: this.$store.state.domain,
            signinDay: 0,
            token: '',
            signinDayGiftList: [],
            signinWeekGiftList: [],
            modal_type: '',
            sign_code: '',
            tipMessage: false,
            tipMsg: '提示',
            tipType: 'success',
            giftModalData: {},
            dayGift: '',
            weekGift: '',
        };
    },
    computed: {
        ...mapState([
            'tipModalStatus'
        ]),
        ...mapActions([
            'tipModalAct'
        ]),
    },
    methods: {
        clickSigninDay() {//点击日签到

            let _this = this;
            let codeLists = getLocalStore('codeLists') || [];
            let token = getLocalStore('token') || '';
            
            signin_day(token)
                .then(function(response) {
                    // console.log(response);
                   if(response.data.state == 1) {
                        let data = response.data.data;
                        let nowTime = new Date();
                        let codeList = {
                            id: data.sign_data.id,
                            title: '微信签到礼包',
                            describe: '',
                            code: data.sign_data.sign_code,
                            expiredStatus: 0,
                            overTime: tiemFormat(nowTime.setDate(nowTime.getDate()+7))
                        };
                        
                        let exist = codeLists.some(function(item, index) {// 查询本地是否存储有，有则直接显示
                            // console.log(item);
                            return item.id == data.sign_data.id;
                        });
                        if(!exist) {
                            codeLists.push(codeList);
                            storeToLocal('codeLists', codeLists);
                        }

                        _this.signinDay = data.count;
                        _this.dayGift = data;
                        _this.modal_type = 'day-signin-success';

                   } else {
                   
                       _this.$store.dispatch('tipModalAct',{msg: response.data.message, type: 'fail'});
                   }
                });
        },
        clickSigninWeek() {//点击周签到
            let _this = this, token = getLocalStore('token');
            let codeLists = getLocalStore('codeLists') || [];

            signin_week(token)
                .then(function(response) {
                    // console.log(response);
                    let data = response.data.data;

                    if(response.data.state == 0) {
                        _this.modal_type = 'get-fail';
                    } else {
                        _this.dayGift = data;
                        _this.modal_type = 'get-gift-success';

                        let codeLists = getLocalStore('codeLists');
                        let nowTime = new Date();
                        let codeList = {
                            id: data.sign_data.id,
                            title: '微信签到周礼包',
                            describe: '',
                            code: data.sign_data.sign_code,
                            expiredStatus: 0,
                            overTime: tiemFormat(nowTime.setDate(nowTime.getDate()+6)),
                            getTime: tiemFormat(Date.now())
                        };
                        if(_this.signinDay <= 7) {
                            _this.signinDay += 1;
                        }
                        codeLists.push(codeList);
                        storeToLocal('codeLists', codeLists);
                    }
                });
        }, 
        closeModal() {
            this.tipMessage = false;
        },
        closeGiftModal() {
            this.modal_type = '';
        },
        initGiftBag(token = '') {
            let _this = this;

            signin_day_gift(token)
                .then(function(response) {
                    // console.log(response);
                    _this.signinDayGiftList = response.data.data;
                }).catch(function(error) {
                    alert(error.toString());
                });
            signin_day_record(token || '')
                .then(function(response) {
                    // console.log(response);
                    if(response.data.state == 1) {
                        _this.signinDay = response.data.data;
                        // _this.signinDay = 3;
                    } else {
                        reject('日签到查询记录失败！');
                    }
                });
            signin_week_gift(token)
                .then(function(response) {
                    // console.log(response);
                    _this.signinWeekGiftList = response.data.data;
                });
        },

    },
    created: function() {
        let code = GetQueryString('code') || '';
        let token = getLocalStore('token') || '';
        let _this = this;

        // console.log(code);
        if(!token) {
           if(code) {
                wxAuth_token(code)
                    .then(function(response) {
                        // console.log(response);
                        storeToLocal('token', response.data.data.token || '');
                        _this.token = response.data.data.token;
                        _this.initGiftBag(response.data.data.token);

                    }).catch(function(error) {
                        alert(error.toString());
                    });
            } else {
                window.location.href = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx88c84ff004115e9e&redirect_uri='
                + encodeURIComponent(window.location.href)
                +'&response_type=code&scope=snsapi_userinfo&state=04baeb821e07ee0ef6a6d42bb451fd00#wechat_redirect';
            }
        }
    },
    mounted: function() {
        let token = getLocalStore('token');
        let _this = this;
        _this.token = token;

        if(!token) { return false; }

        this.initGiftBag(token);
    }
}
</script>
<style lang="scss">
.avtivity-page{
    padding: 100px 0;
    .head-img{
        width: 750px;
        height: 320px;
        background: url('../../assets/head-banner.png') no-repeat;
        background-size: 100% 100%;
    }
    .act-gift{
        width: 150px;
        height: 106px;
        position: relative;
        margin-top: 20px;
        .gift-cover{
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 5;
            width: 150px;
            height: 106px;
            background: rgba(0, 0, 0, .65);
            color: #f5390e;
            font-size: 24px;
            text-align: center;
            line-height: 106px;
        }
        .gift-border{
            position: absolute;
            top: 0;
            left: 0;
            z-index: 10;
            width: 150px;
            height: 106px;
            background: url('../../assets/day-gift-border.png') no-repeat;
            background-size: 100% 100%;
        }
        .gift-content{
            img{
                width: 148px;
                height: 84px;
            }
            p{
                font-size: 14px;
                color: #ffffff;
                text-align: center;
            }
        }
        
    }
    $m-r: 95px;
    .gift-day:nth-child(5){
        margin-left: $m-r;
    }
    .gift-day:nth-child(7){
        margin-right: $m-r;
    }
    .gift-got{
        .gift-cover{
            display: block;
        }
    }
    .get-gift-btn{
        display: block;
        text-decoration: none;
        width: 224px;
        height: 71px;
        line-height: 71px;
        background: url('../../assets/get-gift-btn.png') no-repeat;
        background-size: 100% 100%;
        margin: 37px auto 60px;
        color: #fff2e3;
        font-size: 34px;
        text-align: center;
        letter-spacing: 3px;
        font-weight: 500;
    }
    /* 日签到 */
    .day-sign-in{
        padding: 0 20px;
        margin-top: 20px;
    }
    .day-title{
        height: 60px;
        width: 100%;
        font-size: 30px;
        color: #a690e5;
        .gift-icon{
            width: 41px;
            height: 36px;
            margin-right: 8px;
            background: url('../../assets/gift-icon.png') no-repeat;
            background-size: 41px 36px;
        }
    }
    .day-list{
        border-top: 1px solid #4c4356;/*no*/
    }
    /* 周签到 */
    .week-sign-in{
        padding: 0 20px;
        .week-list{
            border-top: 1px solid #4c4356;/*no*/
            padding: 0 100px;
        }
        .get-week-gift{

        }
    }
    .exchange-code{
        padding: 0 20px;
        p{
            color: #a690e5;
            font-size: 30px;
            margin: 8px 0;
        }
        .exchange-code-content{
            border-top: 1px solid #4c4356;/*no*/
            img{
                margin: 16px 55px 0;
                // width: 330px;
                height: 176px;
            }
        }
    }
    .get-rule{
        padding: 0 20px 80px;
        margin-top: 35px;
        p{
            color: #a690e5;
            font-size: 30px;
            margin: 8px 0;
        }
        .rule-detail{
            border-top: 1px solid #4c4356;/*no*/
            padding-top: 8px;
            p{
                font-size: 24px;
                color: #e2e2e7;
            }
            
        }
    }
}
</style>
