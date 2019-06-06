<template>
    <div id="hotspot">
        <div class="h-head flex-sb-c" v-if="hotspotList">
            <div>
                <img :src="domain + '/' + hotspotList[0].img" alt="image" 
                @click="triggerDialog(hotspotList[0].rule, 1)">
            </div>
            <div>
                <img :src="domain + '/' + hotspotList[1].img" alt="image"
                @click="triggerDialog(hotspotList[1].rule, 1)">
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
        triggerDialog(keyword, type) {
            let _this = this;

            _this.$router.push({path: 'dialog'});
            _this.$store.commit('pushDialogList', {type: 'user', msg: keyword})

            keywordReq(keyword, type, this.urlParams).then((res) => {

                let data = matchKeyWord(res.data.data.content);
                _this.$store.commit('pushDialogList', {type: 'servicer', msg: data});
            })
        }
    },
    computed: {
        ...mapState([
            'baseConf',
            'domain',
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
    width: 900px;
    height: 1020px;
    .h-head{
        width: 866px;
        height: 295px;
        background: url('../../assets/hotspot-head-bg.png') no-repeat;
        background-size: 100% 100%;
        margin: 0 auto;
        padding: 0 15px;
        >div{
            width: 409px;
            height: 228px;
            background: url('../../assets/hotspot-head-item-border.png') no-repeat;
            background-size: 100% 100%;
            padding: 8px;
        }
        img{
            border-radius: 4px;
            width: 393px;
            height: 212px;
        }
    }
    .h-section-tit{
        width: 316px;
        height: 66px;
        background: url('../../assets/hotspot-question.png') no-repeat;
        background-size: 100% 100%;
        margin: 55px auto 30px;
    }
    .h-section-content{
        width: 900px;
        height: 580px;
        padding: 0 34px;
        overflow: scroll;
        .hotspot-list{
            width: 832px;
            height: 97px;
            line-height: 97px;
            background: url('../../assets/hotspot-list-bg.png') no-repeat;
            background-size: 100% 100%;
            margin: 25px 0;
            padding: 0 35px;
            color: #ffffff;
            font-size: 30px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    }
}
</style>
