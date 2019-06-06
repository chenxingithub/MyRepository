<template>
    <div class="gift-all">
        
        <div class="wx-entrance flex-sb-c" v-if="isWeiXin && devFlag">
            <router-link to="/signin" href="JavaScript:;" class="wx-signin">
                <div class="wx-signin-icon"></div>
                <span>微信签到礼包</span>
            </router-link>
            <a href="javascript:;" class="month-gift">
                <div class="month-gift-icon"></div>
                <span>九月道场礼包</span>
            </a>
        </div>
        <p v-if="handle_giftCodeList.length && !devFlag" style="color:#ffffff;font-size: 14px;text-align:center;">暂无礼包码，敬请期待哦~</p>
        <div class="gift-all-list flex-sb-c" v-if="devFlag" v-for="(item, index) in handle_giftCodeList" :key="index">
            <div class="gift-code">
                <p class="gift-code-title">{{ item.cdk_type_title }}</p>
                <p class="gift-code-dec">{{ item.cdk_type_content }}</p>
                <div class="flex percentage-bar-box">
                    <div class="percentage-bar">
                        <span :style="{width: item.surplus/(item.cdk_total_num || 1)* 100 + '%' }"></span>
                    </div>
                    <span>剩余：{{ item.surplus }}</span>
                </div>
            </div>
            <a href="javascript:;" class="get-code" 
            @click="getCode(item.id, item.cdk_type_title,item.cdk_type_content,item.expired_at, item.isGet)">
                {{ item.surplus == 0 ? '已领完' : (item.isGet ? '查看礼包码' : '获取礼包码') }}
            </a>
        </div>
        <!-- 获取礼包码弹窗 -->
        <div class="modal-cover get-code-modal" v-show="getCodeModal == 'show' ? true : false">
            <div class="modal-content">
                <a href="javascript:;" class="close" @click="getCodeModalClose"></a>
                <p class="modal-title">您的礼包码</p>
                <div class="gift-code-bloack">
                    <span class="arrow-icon"></span>
                    <span class="cp-tip">请长按方框内字符串复制礼包码</span>
                </div>
                <div class="gift-code-block">
                    <span>{{ code }}</span>
                </div>
                <p class="check-gift-record">
                    <router-link to="/giftbag/getRecord">查看礼包码领取记录</router-link>
                </p>
            </div>
        </div>
    </div>
</template>
<script>
import { gift_code_list, get_gift_code } from '../../service/ajax.js';
import { GetQueryString, getLocalStore, storeToLocal } from '../../utils/index.js';

export default {
    name: 'giftAll',
    data() {
        return {
            getCodeModal: 'hide',
            isWeiXin: this.$store.state.isWeiXin,
            page: 1,
            limit: 10,
            giftCodeList: [],
            code: '',
            getGiftCodeLists: [],
            devFlag: this.$route.query.dev || ''
        };
    },
    computed: {
        handle_giftCodeList() {
            let lists = [];
            for(let item of this.giftCodeList) {
                
                lists.push(item);
                lists[lists.length-1].isGet = this.isGetCode(item.id);
            }
            return lists;
        }
        
    },
    methods: {
        getCode(id, title, describe, overTime, isGet) {
            let _this = this;
            // console.log(this);
            let giftCodeList = getLocalStore('codeLists') || [];
            
            for(let i = 0 ,len=giftCodeList.length; i < len ; i++) {//本地存在领取记录直接返回礼包码并显示弹窗
                if(giftCodeList[i].id == id) {
                    // console.log(giftCodeList[i].id);
                    // console.log(id);
                    this.code = giftCodeList[i].code;
                    this.getCodeModal = 'show';
                    return false;
                }
            }
            get_gift_code(id)
                .then(function(response) {
                    // console.log(response);
                    let data = response.data.data
                   
                    if(data) {
                         _this.code = data.code;
                    } else {
                        alert(response.data.message);
                        return false;
                    }
                    _this.getCodeModal = 'show';
                    let codeList = {
                        id: id,
                        title: title,
                        describe: describe,
                        code: data.code,
                        overTime: overTime,
                        expiredStatus: 1//默认未过期
                    };

                    giftCodeList.push(codeList);
                    storeToLocal('codeLists',giftCodeList);
                    
                    // console.log(id);
                    for(let index of _this.giftCodeList.keys()) {
                        if(_this.giftCodeList[index].id == id) {

                            // console.log(_this.giftCodeList[index]);
                            if(_this.giftCodeList[index].surplus >=1) {
                                _this.giftCodeList[index].surplus -= 1;
                            }
                            
                            _this.giftCodeList[index].isGet = true;
                            return false;
                        }
                        // console.log(index);
                    }
                    
                    // console.log(codeList);
                });
        },
        getCodeModalClose() {
            this.getCodeModal = 'hide';
        },
        isGetCode (id) {//遍历存储的数据看是否领取过礼包码
            let getGiftCodeLists = this.getGiftCodeLists || [];
            
            return getGiftCodeLists.some(function(item,index) {
                return item.id == id;
            });
        }
    },
    created: function() {
        this.getGiftCodeLists = getLocalStore('codeLists');
    },
    mounted: function() {
        let  _this = this;
        
        //获取礼包列表
        gift_code_list(this.page, this.limit)
            .then(function(response) {
                // console.log(response.data.data);
                _this.giftCodeList = [..._this.giftCodeList , ...response.data.data]
            });
    }
}
</script>
<style lang="scss" scope>
.gift-all{
    width: 710px;
    margin: 0 auto;
}
.get-code-modal{
    .modal-content{
        width: 577px;
        height: 454px;
        background: #c9b098;
        border-radius: 0;
        background: url('../../assets/getCode-modal-bg.png') no-repeat;
        background-size: 100% 100%;
        background-color: #1f1625;
    }
    .close{
        position: absolute;
        top: 0;
        right: 10px;
        width: 50px;
        height: 50px;
        background: url('../../assets/close-icon.png') no-repeat;
        background-size: 37px 37px;
        background-position: center;
    }
    .modal-title{
        font-size: 50px;
        color: #ff310d;
        font-weight: 600;
        margin: 50px 0 30px;
    }
    .gift-code-bloack{
        margin: 20px 0;
    }
    .arrow-icon{
        display: inline-block;
        width: 20px;
        height: 24px;
        background: url('../../assets/arrow-right.png') no-repeat;
        background-size: 100% 100%;
    }
    .gift-code-block{
        user-select: none;
        >span{
            display: inline-block;
            padding: 0 50px;
            background: #e0e0e5;
            height: 58px;
            line-height: 58px;
            border: 1px solid #c9b098;/*no*/
            min-width: 343px;
            user-select: text;
        }
        font-size: 28px;
        color: #1a1a1c;
    }
    .check-gift-record{
        width: 470px;
        border-top: 1px solid #4c4356;/*no*/
        margin: 38px auto 0;
        padding-top: 28px;
    }
}
.wx-entrance{
    padding: 0 5px;
    margin: 35px auto 0;
    text-align: right;
    margin-bottom: 30px;
    
    @mixin signin-head{
        $head-height: 103px;
        position: relative;
        width: 326px;
        height: $head-height;
        line-height: $head-height;
        color: #ffffff;
        font-size: 30px;
        background: url('../../assets/gift-all-head-bg.png') no-repeat;
        background-size: 100% 100%;
        text-decoration: none;
    }
    .wx-signin{
        @include signin-head;
        padding-right: 14px;
    }
    .month-gift{
        @include signin-head;
        padding-right: 20px;
    }
    @mixin signin-icon{
        position: absolute;
        top: 0;
        left: -20px;
    }
    .wx-signin-icon{
        @include signin-icon;
        width: 146px;
        height: 113px;;
        background: url('../../assets/wx-signin-icon.png') no-repeat;
        background-size: 164px 113px;
    }
    .month-gift-icon{
        @include signin-icon;
        width: 146px;
        height: 148px;
        background: url('../../assets/month-signin-icon.png') no-repeat;
        background-size: 146px 148px;
    }
}
.gift-all-list{
    padding-top: 15px;
    border-bottom: 1px solid #4c4356; /*no*/
    .gift-code{
        padding-right: 20px;
        .gift-code-title{
            font-size: 24px;
            color: #ffffff;
            // background: #a7a7b0;
            width: 530px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .gift-code-dec{
            margin: 4px 0;
            font-size: 20px;
            color: #a7a7b0;
            width: 530px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    }
    .percentage-bar-box{
        margin: 10px 0 15px;
    }
    .percentage-bar{
        width: 155px;
        height: 16px;
        border: 1px solid #4e436f;/*no*/
        >span{
            height: 100%;
            width: 50%;
            display: block;
            background: #7b6fa8;
        }
    }
    .percentage-bar + span{
        font-size: 16px;
        color: #585460;
        margin-left: 80px;
    }
    .get-code{
        $height: 44px;
        flex-shrink: 0;
        display: block;
        width: 146px;
        height: $height;
        line-height: $height;
        font-size: 24px;
        color: #fefefe;
        border: 1px solid #7b6fa8;/*no*/
        text-align: center;
        text-decoration: none;
        flex-shrink: 0;
    }
}
</style>
