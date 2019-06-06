<template>
    <div class="articleDetails">
        <Header></Header>
        <div class="article-content">
            <div class="article-title">
                {{ article.title }}
            </div>
            <div class="article-time">
                {{ article.updated_at && article.updated_at.split(' ')[0] }}
            </div>
            <div class="article-text" v-html="article.content">
            </div>
            
            <div class="bottom-nav flex-sb-c">
                <a href="javascript:;">
                    <div class="offical-account flex" @click="changeType('wx-code')">
                        <img src="../../assets/wx-icon.png" alt="offical-account">
                        <span>关注公众号</span>
                    </div>
                </a>
                <a href="javascript:;">
                    <div class="service flex">
                        <img src="../../assets/service-icon.png" alt="service">
                        <router-link tag="span" to="/service">联系客服</router-link>
                    </div>
                </a>
            </div>
            <div class="bottom-dec">
                <p>广州冰鸟网络科技有限公司 粤ICP备 16094894 号-3</p>
                <p>COPYRIGHT @2016 ALLRIGHTS RESERVED</p>
                <p>地址：广州市天河区天河路490号壬丰大厦4019</p>
                <p style="color: #cd2e15;">联系电话：020-38915471 客服电话：020-38915471</p>
            </div>
        </div>
        <click-tip-modal :modalType="clickTipModal_type" @closeModal="closeModal"></click-tip-modal>
        <Footer-nav></Footer-nav>
    </div>
</template>
<script>
import Header from '../header/header';
import FooterNav from '../footer/footer';
import clickTipModal from '../modal/index'
import { request_article } from '../../service/ajax.js'

export default {
    name: 'articleDetails',
    components: {
        Header,
        FooterNav,
        clickTipModal
    },
    data() {
        return {
            id: this.$route.query.id,
            article: {},
            clickTipModal_type: ''
        }
    },
    methods: {
        closeModal(type) {
            this.clickTipModal_type = type;
        },
        changeType(type) {
            this.clickTipModal_type = type;
        },
    },
    mounted: function() {
        // console.log(this.$route.query.id);
        let _this = this;
        request_article(this.id)
            .then(function(response) {
                // console.log(response);
                _this.article = response.data.data;
            });
    }

}
</script>
<style lang="scss" scope>
.articleDetails{
    padding: 100px 35px 100px;
    // min-height: 100vh;
    position: relative;
    // overflow: scroll;
    .article-title{
        font-size: 38px;
        margin-top: 50px;
        text-align: center;
        color: #ffffff;
    }
    .article-time{
        font-size: 20px;
        text-align: center;
        color: #a7a7b0;
        padding: 25px 0;
        border-bottom: 1px solid #4c4356;/*no*/
    }
}

.bottom-nav{
    width: 680px;
    height: 50px;
    margin: 60px auto 30px;
    padding: 0 60px;
    bottom: 370px;
    img{
        width: 50px;
        height: 50px;
    }
    span{
        color: #ffffff;
        font-size: 32px;
        margin-left: 15px;
    }
}
.bottom-dec{
    // position: absolute;
    bottom: 50px;
    width: 680px;
    height: 216px;
    font-size: 24px;
    border-top: 1px solid #72727b;
    color: #84848d;
    padding: 24px 0 0;
    line-height: 40px;
    text-align: center;
}
.article-text{
    min-height: 600px;
    padding-top: 24px;
    color: #a7a7b0;
    text-align: justify;
    text-align-last: initial;
    font-size: 26px;
}
</style>
