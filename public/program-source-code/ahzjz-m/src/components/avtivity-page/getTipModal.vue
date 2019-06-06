<template>
    <div class="get-tip-modal" @touchmove="forbidScroll" v-if="modalType">
        <div class="modal-cover" id="get-fail" v-if="modalType == 'get-fail'">
            <div class="modal-content">
                <div class="close" @click="closeModal"></div>
                <p class="tip-title">领取失败</p>
                <p class="tip-dec">尚未达到周礼包领取条件</p>
                <div class="tip-content">
                    <p>
                        <span class="tip-icon"></span>
                        <span class="tip-text">本周内连续签到7天即可领取每周礼包</span>
                    </p>
                    <div class="gift-display">
                        <div v-for="(item, index) in signinWeekGiftList" :key="index">
                            <img :src="domain+'/'+ item.img_url" alt="image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-cover" id="get-gift-success" v-if="modalType == 'get-gift-success'">
            <div class="modal-content">
                <div class="close"  @click="closeModal"></div>
                <p class="tip-title">领取成功</p>
                <p class="tip-dec">恭喜你获得周礼包</p>
                <div class="tip-content" style="border: none;margin-top: .5rem;margin-bottom: .2rem;">
                    <div class="gift-display">
                        <div v-for="(item, index) in signinWeekGiftList" :key="index">
                            <img :src="domain+'/'+ item.img_url" alt="image">
                        </div>
                    </div>
                </div>
                <div class="get-success-code">
                    <span>{{ dayGift.sign_data.sign_code }}</span>
                    <p>(长按复制即可)</p>
                </div>
                <div class="modal-to-get-record">
                    <router-link to="/giftbag/getRecord">领取记录>></router-link>
                </div>
            </div>
        </div>
        <div class="modal-cover" id="signin-success" v-if="modalType == 'day-signin-success'">
            <div class="modal-content">
                <div class="bg-color"></div>
                <div class="modal-con">
                    <div class="close"  @click="closeModal"></div>
                    <p class="tip-title">签到成功</p>
                    <p class="tip-dec">恭喜你获得礼包</p>
                    <div class="gift-icon">
                        <img :src="domain +'/'+ signinDayGiftList[dayGift.count-1].img_url" alt="image">
                    </div>
                    <div class="get-success-code">
                        <span>{{ dayGift.sign_data.sign_code }}</span>
                        <p>(长按即可复制)</p>
                    </div>
                    <div class="modal-to-get-record">
                        <router-link to="/giftbag/getRecord">领取记录>></router-link>
                    </div>
                    <div class="week-gift-display">
                        <p>
                            <span class="tip-icon"></span>
                            <span>您已连续签到{{ dayGift.count }}天，还差{{ 7 - dayGift.count }}天可以领取周礼包</span>
                        </p>
                        <div class="tip-content" style="border: none;">
                            <div class="gift-display">
                                <div v-for="(item, index) in signinWeekGiftList" :key="index">
                                    <img :src="domain+'/'+ item.img_url" alt="image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'getGiftModal',
    props: [
        'modalType',
        'dayGift',
        'signinDayGiftList',
        'signinWeekGiftList'
    ],
    data() {
        return {
            domain: this.$store.state.domain,
            modal_type: this.modalType
        }
    },
    methods: {
        forbidScroll(e) {
            e.preventDefault();
        },
        closeModal() {
            this.$emit('closegiftmodal');
        }
    },
    mounted: function() {
        console.log(this.dayGift);
    }
}
</script>
<style lang="scss">
.get-tip-modal{
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
    .tip-title{
        font-size: 50px;
        color: #ff310d;
        margin-top: 20px;
    }
    .tip-dec{
        font-size: 28px;
        color: #7b798a;
        margin: 30px 0 20px;
    }
    .tip-content{
        width: 505px;
        margin: 0 auto;
        border-top: 1px solid #4c4356;
        >p{
            margin: 28px 0;
        }
    }
    .gift-display{
        display: flex;
        justify-content: center;
        >div{
            $border-radius: 10px;
            width: 148px;
            height: 84px;
            // width: 130px;
            // height: 130px;
            border: 2px solid #e2d5d1;/*no*/
            border-radius: $border-radius;
            overflow: hidden;
            margin: 0 10px;
            >img{
                width: 100%;
                height: 100%;
            }
        }
    }
    .get-success-code{
        user-select: none;
        >span{
            width: 440px;
            display: inline-block;
            height: 58px;
            line-height: 58px;
            background: #e0e0e5;
            color: #1a1a1c;
            padding: 0 30px;
            font-size: 28px;
            user-select: text;
            margin: 20px 0;
        }
        >p{
            font-size: 28px;
            color: #7b798a;
        }
    }

    .modal-to-get-record{
        text-align: right;
        padding-right: 50px;
        color: #bc2629;
        margin-top: 20px;
        a{
            color: #bc2629;
        }
        a:link{
            text-decoration: underline;
        }
    }
    .tip-icon{
        display: inline-block;
        width: 20px;
        height: 24px;
        background: url('../../assets/arrow-right.png') no-repeat;
        background-size: 100% 100%;
    }
}
/*领取失败*/
#get-fail{
    display: block;
    .modal-content{
        width: 577px;
        height: 454px;
        background: url('../../assets/get-gift-modal.png') no-repeat;
        background-size: 100% 100%;
        background-color: #1f1625;
    }
}
/*领取成功*/
#get-gift-success{
    display: block;
    .modal-content{
        width: 577px;
        height: 514px;
        background: url('../../assets/get-success-modal-bg.png') no-repeat;
        background-size: 100% 100%;
        background-color: #1f1625;
    }
}
/*签到成功*/
#signin-success{
    display: block;
    .modal-content{
        width: 577px;
        height: 743px;
    }
    .bg-color{
        position: absolute;
        left: 0;
        top: 0;
        z-index: -1;
        width: 577px;
        height: 743px;
        background: linear-gradient(to bottom, #1f1625 503px, #33283c 240px);
    }
    .modal-con{
        position: absolute;
        left: 0;
        top: 0;
        z-index: 1;
        width: 577px;
        height: 743px;
         background: url('../../assets/signin-success-modal-bg.png') no-repeat;
        background-size: 100% 100%;
    }
    .gift-icon{
        $border-radius: 10px;
        width: 148px;
        height: 94px;
        // width: 130px;
        // height: 130px;
        border: 2px solid #e2d5d1;/*no*/
        border-radius: $border-radius;
        overflow: hidden;
        margin: 20px auto;
        >img{
            width: 100%;
            height: 100%;
        }
    }
    .week-gift-display{
        position: absolute;
        top: 530px;
        width: 573px;
        height: 240px;
        font-size: 28px;
    }
    .gift-display{
        padding-top: 25px;
    }
    .tip-dec{
        margin: 20px 0 0 0;
    }
}
</style>
