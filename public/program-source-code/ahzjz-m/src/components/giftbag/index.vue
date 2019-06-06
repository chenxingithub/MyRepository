<template>
    <div id="gift-bag">
        <Header></Header>
        <header></header>
        <div class="gift-content">
            <div class="gift-nav flex">
                <router-link to="/giftbag/getRecord" tag="div"
                :class="['get-record', this.$route.name == 'getRecord' ? 'nav-selected' : '']">领取记录</router-link>
                <router-link to="/giftbag/giftAll"  tag="div"
                :class="['gift-all', this.$route.name == 'giftAll' ? 'nav-selected' : '']" 
                @click="exchangeNav('giftAll')">所有礼包</router-link>
                <router-link to="/giftbag/howExchange" tag="div"
                :class="['how-exchange', this.$route.name == 'howExchange' ? 'nav-selected' : '']" 
                @click="exchangeNav('howExchange')">如何兑换</router-link>
            </div>
            <!-- <get-record v-show="nav_selected == 'getRecord' ? true : false"></get-record>
            <gift-all v-show="nav_selected == 'giftAll' ? true : false"></gift-all>
            <how-to-exchange v-show="nav_selected == 'howExchange' ? true : false"></how-to-exchange> -->
            <router-view></router-view>
        </div>
        <Footer-nav></Footer-nav>
    </div>
</template>
<script>
import Header from '../header/header';
import FooterNav from '../footer/footer';
import getRecord from './getRecord.vue';
import giftAll from './giftAll';
import howToExchange  from './howToExchange';

export default {
    name: 'giftBag',
    components: {
        Header,
        FooterNav,
        // getRecord,
        // giftAll,
        // howToExchange
    },
    data() {
        return {
            nav_selected: 'giftAll',
            isWeiXin: this.$store.state.isWeiXin
        }
    },
    methods: {
        exchangeNav(nav) {
            this.nav_selected = nav;
        }
    }
}
</script>
<style lang="scss">
#gift-bag{
    padding: 100px 0;
    min-height: 100vh;
    header{
        width: 750px;
        height: 280px;
        background: url('../../assets/head-banner.png') no-repeat;
        background-size: 100% 100%;
    }
    .gift-content{
        width: 750px;
        padding: 0 20px;
        .gift-nav{
            height: 50px;
            width: 100%;
            margin: 30px auto 35px;
            border: 1px solid #eee7e2; /*no*/
            >div{
                width: 33.33%;
                text-align: center;
                font-size: 24px;
                color: #cdccd6;
            }
            .nav-selected{
                height: 100%;
                line-height: 50px;
                border-left: 1px solid #eee7e2; /*no*/
                border-right: 1px solid #eee7e2; /*no*/
                background: url('../../assets/nav-selected-bg.png') no-repeat;
                background-size: 100% 23px;
                background-position: 0 center;
                background-color: #6e5ea0;
            }
        }
    }
}
</style>
