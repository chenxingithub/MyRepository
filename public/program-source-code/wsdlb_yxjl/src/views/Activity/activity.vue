<template>
    <div id="activity">
        <div class="activity-box">
            <div class="activity-content-box activity-wrapper">
                <div class="content">
                    <div class="activity-content" v-for="(item, index) in activityList" :key="index" @click="triggerDialog(item.rule, 1)">
                        <div class="activity-list flex-sb-c">
                            <div class="activity-list-icon">
                                <img :src="domain + '/' + item.img" alt="image">
                            </div>
                            <div class="activity-list-text">
                                <p>{{ item.title }}</p>
                                <p :style="{'color': item.content_color}">{{ item.content }}</p>
                            </div>
                        </div>
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

export default {
    name: 'activity',
    data() {
        return {
            activityList: null
        }
    },
    methods: {
        requestActivitycont() {//请求板块内容
            let _this = this;

            ruleComponentReq(this.baseConf[2].id)
                .then(function(res) {

                    _this.activityList = res.data.data;
                })
        },
        triggerDialog(keyword, type) {

            let _this = this;

            _this.$router.push({path: 'dialog'});
            _this.$store.commit('pushDialogList', {type: 'user', msg: keyword})

            keywordReq(keyword, type, this.urlParams).then((res) => {
                let data = res.data.data.content;
                
                _this.$store.dispatch('syncPushDialogList', {type: 'servicer', msg: data});
            });
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
            this.requestActivitycont();
        }
    },
    mounted() {
        let wrapper = document.querySelector('.activity-wrapper');
        this.scroll = new BScroll(wrapper, {click: true})

    },
    watch: {
        'baseConf': 'requestActivitycont'
    }
}
</script>
<style lang="scss">
#activity{
    padding: 30px 16px 0;
    width: 900px;
    height: 1020px;
    
    .activity-box{
        width: 868px;
        height: 965px;
        background: url('../../assets/activity-bg.png') no-repeat;
        background-size: 868px 965px;
        overflow: scroll;
        padding-top: 9px;
    }
    .activity-content-box{
        padding-top: 20px;
        height: 948px;
        overflow: auto;
    }
    .activity-content{
        margin: 0 auto;
        width: 847px;
        height: 215px;
        background: url('../../assets/activity-list-bg.png') no-repeat;
        background-size: 100% 100%;
        padding: 10px 6px 0;
        margin-bottom: 18px;
        z-index: -1;
    }
    .activity-list-icon{
        width: 197px;
        height: 199px;
        background: url('../../assets/activity-list-img-border.png') no-repeat;
        background-size: 100% 100%;
        // background-color: aquamarine;
        padding: 14px 12px;
        img{
            width: 168px;
            height: 168px;
        }
    }
    .activity-list-text{
        padding: 20px 28px;
        width: 630px;
        height: 200px;
        // background: skyblue;
        color: #ffffff;
        p:nth-child(1){
            font-size: 34px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        p:nth-child(2){
            margin-top: 20px;
            font-size: 30px;
            overflow : hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
    }
}
</style>
