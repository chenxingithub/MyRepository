<template>
    <div id="footer-nav">
        <div class="footer-bg"></div>
        <div class="footer-nav flex-sb-c">
            <router-link to="/index" :class="select_nav == 'homepage' ? 'selected' : ''" @click="toggle_nav('homepage')" >
                <span class="footer-nav-icon homepage"></span>
                <span class="footer-nav-txt">首页</span>
            </router-link>
            <router-link :to="{path: '/listNav', query: {type: 'gameStrategy', id: 49}}" :class="select_nav == 'strategy' ? 'selected' : ''" @click="toggle_nav('strategy')">
                <span class="footer-nav-icon strategy"></span>
                <span class="footer-nav-txt">攻略</span>
            </router-link>
            <router-link to="/giftbag" :class="select_nav == 'gift' ? 'selected' : ''" @click="toggle_nav('gift')">
                <span class="footer-nav-icon gift"></span>
                <span class="footer-nav-txt">礼包</span>
            </router-link>
            
            <router-link to="/openserivce" :class="select_nav == 'open-server' ? 'selected' : ''" @click="toggle_nav('open-server')">
                <span class="footer-nav-icon open-server"></span>
                <span class="footer-nav-txt">开服</span>
            </router-link>
            <router-link to="/service" :class="select_nav == 'service' ? 'selected' : ''" @click="toggle_nav('service')">
                <span class="footer-nav-icon service"></span>
                <span class="footer-nav-txt">客服</span>
            </router-link>
        </div>
    </div>
</template>
<script>
export default {
    name: 'footerNav',
    data() {
        return {
            select_nav: 'homepage'
        };
    },
    mounted: function() {
        switch (this.$route.name) {
            case 'homePage':
                this.select_nav = 'homepage';
                break;
            case 'listNav':
                this.select_nav = 'strategy';
                break;
            case 'articleDetails':
                this.select_nav = 'strategy';
                break;
            case 'giftAll':
                this.select_nav = 'gift';
                break;
            case 'getRecord':
                this.select_nav = 'gift';
                break;
            case 'howExchange':
                this.select_nav = 'gift';
                break;
            case 'activityPage':
                this.select_nav = 'gift';
                break;
            case 'openService' :
                this.select_nav = 'open-server';
                break;
            case 'service':
                this.select_nav = 'service';
                break;
            default:
                this.select_nav = '';
                break;
        }
    },
    methods: {
        toggle_nav(nav_id) {
            this.select_nav = nav_id;
        }
    }
}
</script>
<style lang="scss">
#footer-nav{
    position: fixed;
    left: 50%;
    bottom: 0;
    margin-left: -375px;
    width: 750px;
    height: 116px;
    z-index: 999;
    .footer-bg{
        width: 750px;
        height: 25px;
        background: url('../../assets/footer-bg.png') no-repeat;
        background-size: 750px 25px;
        background-position: center;
    }
    .footer-nav{
        width: 750px;
        height: 100px;
        padding: 0 25px;
        background: #070409;
        >a{
            width: 140px;
            height: 100px;
            text-align: center;
            font-size: 20px;
            color: #a2a2ab;
            display: block;
            text-decoration: none;
        }
        .footer-nav-icon{
            display: block;
            width: 36px;
            height: 36px;
            margin: 0 auto;
            background: url('../../assets/footer-nav.png') no-repeat;
            background-size: 331px 159px;
            margin: 14px auto 5px;
        }
        .homepage{
            background-position: -22px -22px
        }
        .strategy{
            background-position: -85px -20px
        }
        .gift{
            background-position: -146px -20px
        }
        .open-server{
            background-position: -208px -20px
        }
        .service{
            background-position: -273px -20px
        }
        .selected{
            color: #e5b99d;
            .footer-nav-icon{
                background-position-y: -105px;
            }
        }
    }
}
</style>
