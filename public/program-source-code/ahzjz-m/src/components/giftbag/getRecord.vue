<template>
    <div class="get-record">
        <p v-if="!codeLists.length" style="color:#ffffff;font-size: 14px;text-align:center;">暂无领取记录</p>
        <div class="record-list" v-for="(item, index) in codeLists" :key="index">
            <p class="record-title">{{ item.title }}</p>
            <p class="record-dec">{{ item.describe }}</p>
            <p class="record-dec" v-if="item.getTime">当前领取时间{{ item.getTime.split(' ')[0] }}</p>
            <div class="record-time-limit flex-sb-c">
                <span>礼包有限期：{{ item.overTime.split(' ')[0] }}</span>
                <div v-if="item.expiredStatus != 1" class="time-out-flag">
                    <span>{{ item.expiredStatus == 0 ? '即将过期' : '已过期' }}</span>
                </div>
            </div>
            <p class="code-cp-tip"><span></span>请长按下方礼包码复制</p>
            <div class="gift-code"><span>{{ item.code }}</span></div>
        </div>
        
    </div>
</template>
<script>
import { GetQueryString, getLocalStore, storeToLocal } from '../../utils/index.js';

export default {
    name: 'getRecord',
    data() {
        return {
            codeLists: []
        };
    },
    methods: {
        codeListTTimeStatus() {

        }
    },
    created: function() {
        let codeLists, arr = [], nowTime = Date.now(),
        toExpire=null,expired=null,time=null;
        let day_7 = 1000*60*60*24*7;
        
        codeLists = getLocalStore('codeLists') || [];

        for(let i = 0, len = codeLists.length; i < len; i++) {
            // console.log(codeLists[i].overTime);
            if((codeLists[i].overTime + '').indexOf('-') > 0) {
                time = Date.parse(codeLists[i].overTime.replace(/\-/g,'/'));
            } else if((codeLists[i].overTime + '').indexOf('/') > 0) {
                time = Date.parse(codeLists[i].overTime);
            } else {
                time = codeLists[i].overTime;
            }
            toExpire = time - day_7;
            expired = time + day_7;

            if(nowTime < toExpire) {//未过期
                codeLists[i].expiredStatus = 1;
            }
            if(toExpire < nowTime && nowTime < time) {//即将过期
                codeLists[i].expiredStatus = 0;
            }
            if(time <= nowTime && nowTime <= expired) {//已过期（七天内保存显示）
                codeLists[i].expiredStatus = -1;
            }
           
            if(expired < nowTime) {//过期七天后删除
                arr.push(i);
            }
            
        }
        for(let j = 0, len = arr.length; j < len; j++) {
            codeLists.splice(arr[j],1);//删除过期礼包记录
        }
        storeToLocal('codeLists', codeLists);
        this.codeLists = codeLists.reverse();
    }
}
</script>
<style lang="scss">
.get-record{
    width: 710px;
    margin: 0 auto;
    .record-list{
        border-bottom: 1px solid #4c4356;
        padding-top: 10px;
        .record-title{
            font-size: 30px;
            color: #fefefe;
            margin: 10px 0;
        }
        @mixin font-color{
            font-size: 22px;
            color: #878592;
        }
        .record-dec{
           @include font-color;
           margin-top: 10px;
        }
        .record-time-limit{
            @include font-color;
            margin-bottom: 10px;
        }
        .time-out-flag{
            min-width: 110px;
            font-size: 18px;
            border: 2px solid #e5b99d;/*no*/
            color: #e5b99d;
            padding: 2px 5px 0;
            text-align: center;
            vertical-align: middle;
            align-self: center;
        }
        .code-cp-tip{
            font-size: 24px;
            color: #fffefe;
            margin-bottom: 10px;
            span{
                display: inline-block;
                width: 20px;
                height: 24px;
                margin-right: 10px;
                background: url('../../assets/arrow-right.png') no-repeat;
                background-size: 20px 24px;
            }
        }
        .gift-code{
            margin: 25px 0;
            display: inline-block;
            font-size: 28px;
            color: #000000;
            width: 500px;
            height: 50px;
            line-height: 50px;
            padding: 0 25px;
            background: #ffffff;
            border: 1px solid #c9b098;
            -webkit-user-select: none;
            user-select: none;
            text-align: center;
            >span{
                -webkit-user-select: text;
                user-select: text;
            }
        }
    }
}
</style>
