<template>
    <div id="publishFirst-welfare">
        <div class="public-title"></div>
        <div class="public-content flex-wrap-sb-c" ref="publishFirs">
            <div v-for="(item, index) in welfares" 
            @click="showPublishFirstModal(item.title, item.subtitled, item.id, item.jump_url)"
            :key="index" :style="{display: animate ? 'block': 'none'}" class="zoomIn animated">
                <a href="javascript:;">
                    <img :src="domain + '/' + item.icon" alt="images">
                </a>
            </div>
        </div>
        <div class="arrow-down"></div>
        <div class="publishFirstWelfareModal modal-cover" v-show="modalStatus == 'show' ? true : false">
            <div class="modal-content">
                <div class="welfare-title">
                    <p>{{ publishFirstModal.title }}</p>
                </div>
                <div class="welfare-dec-box">
                    <div class="welfare-arrow">
                        <img src="../../assets/arrow-right.png" alt="arrow">
                    </div>
                    <div class="welfare-dec">
                        <div v-html="publishFirstModal.subtitled"></div>
                        <!-- {{ publishFirstModal.subtitled }} -->
                        <router-link v-if="!publishFirstModal.jump_url"
                        :to="{path: '/articledetails', query: {id: publishFirstModal.id}}">进入正文>></router-link>

                        <a v-if="publishFirstModal.jump_url"
                        :href="publishFirstModal.jump_url">进入正文>></a>
                    </div>
                </div>
                <div class="close" @click="closeModal"></div>
            </div>
        </div>
    </div>
</template>
<script>
import { request_articleList } from '../../service/ajax.js'
import { throttle } from '../../utils/index.js';

export default {
    name: 'publishFirst_welfare',
    data() {
        return {
            welfares: [],
            domain: this.$store.state.domain,
            animate: false,
            throttleLoad: null,
            publishFirstModal: {
                title: '',
                subtitled: '',
                id: '',
                jump_url: ''
            },
            modalStatus: 'hide'
        }
    },
    methods:{
        publishFirstScroll() {
            // console.log('111');
            if(this.animate) {
                return false;
            }
            if(!this.$refs.publishFirs) {
                return false;
            }

            if(this.$refs.publishFirs.getBoundingClientRect().top < window.screen.height - 350) {
                this.animate = true;
            }
        },
        showPublishFirstModal(title, subtitled, id, jump_url) {
            
            this.modalStatus = 'show';
            this.publishFirstModal = {title, subtitled, id, jump_url};

        },
        closeModal() {
            this.modalStatus = 'hide'
        }

    },
    mounted: function() {
        let articleId = 47, page = 1, limit = 6;
        let _this = this;
        
        request_articleList(articleId, page, limit)
            .then(function(response) {
                let data = response.data[articleId].data[0].data;
                let len = data.length, temp;

                for (let i = 0; i < len - 1; i++) {//根据id排序 升序
                    for (let j = 0; j < len - 1 - i; j++) {
                        if (data[j].id > data[j+1].id) {
                            temp = data[j+1];
                            data[j+1] = data[j];
                            data[j] = temp;
                        }
                    }
                }
                 _this.welfares = data;
                
            });
        this.throttleLoad = throttle(_this.publishFirstScroll,1000, 500, this);
        window.addEventListener('scroll', this.throttleLoad, false);
    },
    beforeRouteLeave (to, from, next) {
        window.removeEventListener('scroll', this.throttleLoad);
    }
}
</script>
<style lang='scss'>
#publishFirst-welfare{
    width: 750px;
    height: 1209px;
    padding-top: 40px;
    background: url('../../assets/entry-publish-welfare.jpg') no-repeat;
    background-size: 100% 100%;
    .zoomIn{
        display: none;
    }
    .public-title{
        width: 720px;
        height: 62px;
        margin: 0 auto;
        background: url('../../assets/entry-publish-title.png') no-repeat;
        background-size: 100% 100%;
    }
    .public-content{
        padding: 0 42px;
        margin-top: 30px;
        min-height: 1038px;
        &>div{
            width: 302px;
            height: 306px;
            background: url('../../assets/entry-publish-content-bg.png') no-repeat;
            background-size: 100% 100%;
            margin-bottom: 40px;
            >a{
                display: block;
                width: 266px;
                height: 281px;
                border-radius: 20px;
                margin: 12px 0 0 18px;
                overflow: hidden;
                img{
                    width: 100%;
                    height: 100%;
                }
            }
        }
    }
    .arrow-down{
        margin: 0 auto;
        width: 87px;
        height: 40px;
        background: url('../../assets/arrow-down.png') no-repeat;
        background-size: 100% 100%
    }
    .publishFirstWelfareModal{
        .modal-content{
            width: 577px;
            height: 454px;
            background-image: url('../../assets/getCode-modal-bg.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-color: #1c141f;
            padding: 0 48px;
        }
        .welfare-title{
            color: #ff2d0b;
            font-size: 50px;
            text-align: center;
            font-weight: 600;
            height: 100px;
            line-height: 100px;
            margin-top: 15px;
            border-bottom: 1px solid #463e49; /*no*/
        }
        .welfare-dec-box{
            display: flex;
            padding: 26px 32px 0;
            text-align: left;
            height: 300px;
            overflow: scroll;
            line-height: 40px;
            font-size: 28px;
            a{
                color: #ff2d0b;
            }
            a:link{
                text-decoration: underline;
            }
        }
        .welfare-arrow{
            width: 35px;
            flex-shrink: 0;
            img{
                width: 24px;
                height: 30px;
            }
        }
        .welfare-dec{
            font-size: 28px;
            color: #fffefe;
        }
        .close{
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 60px;
            background: url('../../assets/close-icon.png') no-repeat;
            background-size: 37px 37px;
            background-position: center;
        }
    }
    
}
</style>
