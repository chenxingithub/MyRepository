<template>
    <div id="hotspot">

        <div class="h-head flex-sb-c" v-if="hotspotList">
            <div class="h-head-icon" :style="{'background-image': 'url('+ imgDomain + '/' + hotspotList[0].img +')'}">
                <div class="h-head-bg" @click="triggerDialog(hotspotList[0].rule, 1)">
                </div>
            </div>
            <div class="h-head-icon" :style="{'background-image': 'url('+ imgDomain + '/' + hotspotList[1].img +')'}">
                <div class="h-head-bg" @click="triggerDialog(hotspotList[1].rule, 1)">
                </div>
            </div>
        </div>
        <div class="h-section">
            <div class="h-section-tit"></div>
            <div class="h-section-content hotspot-wrapper">
                <div class="content">
                    <div class="hotspot-list" v-for="(item, index) in hotspotList" :key="index"
                    v-if="index >= 2" @click="triggerDialog(item.rule, 1)">
                        {{ item.title }}
                    </div>
                </div>
            </div>
            <div class="h-border-bar"></div>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex'
import BScroll from 'better-scroll'
import { ruleComponentReq, keywordReq } from '../../api/request.js'
import { matchKeyWord } from '../../utils/utils.js'

export default {
    name: 'hotspot',
    data() {
        return {
            hotspotList: null
        }
    },
    methods: {
        requestHotspotcont() {//请求板块内容
            let _this = this;

            ruleComponentReq(this.baseConf[0].id)
                .then(function(res) {

                    _this.hotspotList = res.data.data;
                })
        },
        triggerDialog(keyword, type) {//请求对话后保存
            let _this = this;

            _this.$router.push({path: 'dialog'});
            _this.$store.commit('pushDialogList', {type: 'user', msg: keyword})

            keywordReq(keyword, type, this.urlParams).then((res) => {

                let data = res.data.data;
                let msg = '';

                if(data) {
                    msg = matchKeyWord(data.content);
                } else {
                    msg = '敬请期待';
                }
                _this.$store.dispatch('syncPushDialogList', {type: 'servicer', msg: msg});
            })
        }
    },
    computed: {
        ...mapState([
            'baseConf',
            'imgDomain',
            'dialogList',
            'urlParams'
        ]),
    },
    created() {
        if(this.baseConf) {
            this.requestHotspotcont();
        }
    },
    mounted() {
        let wrapper = document.querySelector('.hotspot-wrapper')
        let scroll = new BScroll(wrapper, {click: true})
    },
    watch: {
        'baseConf': 'requestHotspotcont'
    }
}
</script>
<style lang="scss">
#hotspot{
    width: 825px;
    height: 1188px;
    margin: 0 auto;
    .h-head{
        width: 825px;
        height: 295px;
        margin: 0 auto;
        // padding: 0 15px;
        .h-head-icon{
            width: 393px;
            height: 218px;
            background-size: 391px 216px;
            background-position: center;
        }
        img{
            border-radius: 4px;
            width: 380px;
            height: 212px;
        }
        .h-head-bg{
            width: 393px;
            height: 218px;
            background: url('../../assets/hotspot-head-item-border.png') no-repeat;
            background-size: 100% 100%;
        }
    }
    .h-section-tit{
        // width: 173px;
        height: 55px;
        background: url('../../assets/hotspot-question.png') no-repeat;
        background-size: 173px 46px;
        // background-position: left top;
        margin: 20px 0 0px 0;
        border-bottom: 1px solid #5b5b5b; /*no*/
    }
    .h-section-content{
        width: 100%;
        height: 810px;
        overflow: scroll;
        margin-top: 5px;
        .content{
             padding-top: 15px;
        }
        .hotspot-list{
            width: 816px;
            height: 97px;
            line-height: 97px;
            // background: url('../../assets/hotspot-list-bg.png') no-repeat;
            // background-size: 100% 100%;
            background: rgba(67,67,67, 0.85);
            margin: 25px 0;
            padding: 0 35px;
            color: #ffffff;
            font-size: 30px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            border: 1px solid #959595; /*no*/
        }
    }
    .h-border-bar{
        width: 809px;
        height: 3px;
        background: url('../../assets/hs-border-bar.png') no-repeat;
        background-size: 100% 100%;
        margin: 10px auto 0;
    }
}
</style>
