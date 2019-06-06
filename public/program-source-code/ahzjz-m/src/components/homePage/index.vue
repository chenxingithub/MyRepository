<template>
    <div id="home-page">
        <a href="https://bbs.310game.com/forum.php?mod=forumdisplay&fid=40" class="service" ref="dragBox">
        </a>
        <Header></Header>
        <head-banner :headFocus="focus_pictures['2']"></head-banner>
        <Footer-nav></Footer-nav>
        <announcement :announcements="[articleLists['48'], articleLists['50'], articleLists['51']]"></announcement>
        <mercenary-introduce :mercenaryFocus="focus_pictures['3']"></mercenary-introduce>
        <gameStrategy></gameStrategy>
        <bottom @changeType="changeModalType"></bottom>
        <click-tip-modal :modalType="clickTipModal_type" @closeModal="closeModal"></click-tip-modal>
    </div>
</template>
<script>
import Header from '../header/header';
import FooterNav from '../footer/footer';
import headBanner from './head-banner';
import announcement from './announcement';
import mercenaryIntroduce from './mercenary-introduce';
import gameStrategy from './game-strategy';
import bottom from './bottom';
import clickTipModal from '../modal/index'
import { drag } from '../../utils/index.js';
import * as http from '../../service/ajax.js';
import { mapState, mapMutations, mapActions } from 'vuex';
import { request_focus_picture, request_articleList } from '../../service/ajax.js'

let a = "background: #9B30FF;color: #ffffff;font-size: 14px;padding: 3px;"

export default {
    name: 'homePage',
    components: {
        Header,
        FooterNav,
        headBanner,
        announcement,
        mercenaryIntroduce,
        gameStrategy,
        bottom,
        clickTipModal
    },
    data() {
        return {
            domain: http.domain,
            clickTipModal_type: '',
            baseInfo: {},
            focus_pictures: [],
            articleLists: {}
        };
    },
    computed: {
       _baseInfo() {
           return this.$store.state.baseInfo;
       }
    },
    created() {
        let _this = this,
            focus_type = '2,3',
            articleId = '48,49,50,51,52,53',
            artPage = 1,
            artLimit = 5;


        request_focus_picture(focus_type)
            .then(function(response) {

                _this.focus_pictures = response.data.data;
            });
        // request_articleList(articleId, artPage, artLimit)
        //     .then(function(response) {
        //         console.log(response.data);
        //         _this.articleLists = response.data;
        //     });
    },
    mounted() {

        // console.log('%c index mounted', a);
        drag(true, true, this.$refs.dragBox);

    },
    methods: {
        changeModalType(type) {
            this.clickTipModal_type = type;
        },
        closeModal(param) {
            this. clickTipModal_type = param;
        }
    }
}
</script>
<style lang="scss" scoped>
#home-page{
    background: #1f1625;
    width: 750px;
    height: auto;
    padding: 100px 0 100px 0;
    // overflow: scroll;
    .service{
        display: block;
        position: fixed;
        top: 400px;
        right: 0;
        z-index: 2222;
        width: 132px;
        height: 135px;
        background: url('../../assets/lt-icon.gif') no-repeat;
        background-size: 132px 135px;
    }
}
</style>
