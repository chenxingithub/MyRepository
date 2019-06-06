<template>
    <div id="announcement">
        <div class="announce-title"></div>
        <router-link :to="{path: '/listNav', query: {type: 'announcement', id: 48}}" class="announce-more">更多＋</router-link>
        <swiper :options="announce_swiperOption" ref="announce-swipe" id="swiper-content">
            <div class="announce-swiper-pagination"  slot="pagination">
            </div>
            
            <!-- <swiper-slide>
                <div class="headline flex-sb-c">
                    <div class="headline-img-border">
                        <div class="headline-img"></div>
                    </div>
                    <div class="headline-content">
                        <div class="headline-title">
                            【暗黑终结者】 安卓634服米尔岗口开服米尔岗口开
                        </div>
                        <div class="headline-text">
                            安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！
                        </div>
                    </div>
                    <a class="headline-detial" href="javascript:;">详情>></a>
                </div>
                
                <a href="javascript:;">
                    <div class="announce-list">
                        <span></span>
                        <div>
                            <p class="announce-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                            <p class="announce-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                        </div>
                    </div>
                </a>
                <a href="javascript:;">
                    <div class="announce-list">
                        <span></span>
                        <div>
                            <p class="announce-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                            <p class="announce-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                        </div>
                    </div>
                </a>
                <a href="javascript:;">
                    <div class="announce-list">
                        <span></span>
                        <div>
                            <p class="announce-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                            <p class="announce-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                        </div>
                    </div>
                </a>
            </swiper-slide>
            <swiper-slide>
                <div class="headline flex-sb-c">
                    <div class="headline-img-border">
                        <div class="headline-img"></div>
                    </div>
                    <div class="headline-content">
                        <div class="headline-title">
                            【暗黑终结者】 安卓634服米尔岗口开服米尔岗口开
                        </div>
                        <div class="headline-text">
                            安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！
                        </div>
                    </div>
                    <a class="headline-detial" href="javascript:;">详情>></a>
                </div>
                
                <a href="javascript:;">
                    <div class="announce-list">
                        <span></span>
                        <div>
                            <p class="announce-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                            <p class="announce-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                        </div>
                    </div>
                </a>
                <a href="javascript:;">
                    <div class="announce-list">
                        <span></span>
                        <div>
                            <p class="announce-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                            <p class="announce-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                        </div>
                    </div>
                </a>
                <a href="javascript:;">
                    <div class="announce-list">
                        <span></span>
                        <div>
                            <p class="announce-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                            <p class="announce-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                        </div>
                    </div>
                </a>
            </swiper-slide> -->
            <swiper-slide v-for="(items, index) in announcementList" :key="index">
                <router-link v-if="items.data[0].data[0].jump_url == ''"
                    :to="{path: '/articledetails', query: {id: items.data[0].data[0].id} }" class="headline flex-sb-c">
                    <div class="headline-img-border">
                        <div class="headline-img" 
                        :style="{backgroundImage: 'url('+ (items.data[0].data[0].icon ? (domain +'/'+ items.data[0].data[0].icon) : './static/headline-image.png') +')'}"></div>
                    </div>
                    <div class="headline-content">
                        <div class="headline-title">
                            {{ items.data[0].data[0].title }}
                        </div>
                        <div class="headline-text">
                            {{ items.data[0].data[0].subtitled.length > 34 ? (items.data[0].data[0].subtitled.slice(0,34) + '...') : items.data[0].data[0].subtitled.slice(0,34) }}
                        </div>
                    </div>
                    <a class="headline-detial">
                        详情>>
                    </a>
                    <!-- <a  class="headline-detial">详情>></a> -->
                    <!-- <a class="headline-detial" href="javascript:;">详情>></a> -->
                </router-link>
                <a :href="items.data[0].data[0].jump_url" v-if="items.data[0].data[0].jump_url" class="headline flex-sb-c">
                    <div class="headline-img-border">
                        <div class="headline-img" 
                        :style="{backgroundImage: 'url('+ (items.data[0].data[0].icon ? (domain +'/'+ items.data[0].data[0].icon) : './static/headline-image.png') +')'}"></div>
                    </div>
                    <div class="headline-content">
                        <div class="headline-title">
                            {{ items.data[0].data[0].title }}
                        </div>
                        <div class="headline-text">
                            {{ items.data[0].data[0].subtitled.length > 34 ? (items.data[0].data[0].subtitled.slice(0,34) + '...') : items.data[0].data[0].subtitled.slice(0,34) }}
                        </div>
                    </div>
                    <a class="headline-detial">
                    详情>>
                    </a>
                </a>
                <div 
                v-for="(item, index) in items.data[0].data" 
                :key="index" v-if="0 < index && index < 4">
                    <router-link :to="{path: '/articledetails', query: {id: item.id} }" v-if="!item.jump_url">
                        <div class="announce-list">
                            <span></span>
                            <div>
                                <p class="announce-list-tit">【{{ items.name[0] == '暗黑公告' ? '综合' : items.name[0]}}】{{ item.title }}</p>
                                <p class="announce-list-dec">{{ item.subtitled }}</p>
                            </div>
                        </div>
                    </router-link>
                    <a :href="item.jump_url" v-if="item.jump_url">
                         <div class="announce-list">
                            <span></span>
                            <div>
                                <p class="announce-list-tit">【{{ items.name[0] == '暗黑公告' ? '综合' : items.name[0]}}】{{ item.title }}</p>
                                <p class="announce-list-dec">{{ item.subtitled }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </swiper-slide>
        </swiper>
    </div>
</template>
<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
import 'swiper/dist/css/swiper.css'
import { request_focus_picture, request_articleList } from '../../service/ajax.js'

let a = "background: #e33100; color: #ffffff;font-size: 14px;"

export default {
    name: 'announcement',
    components: {
        swiper,
        swiperSlide
    },
    props: ['announcements'],
    data() {
        return {
            domain: this.$store.state.domain,
            announcementList: {},
            announce_swiperOption: {
                 pagination: {
                    el: '.announce-swiper-pagination',
                    clickable: true,
                    renderBullet: function (index, className) {
                        let text = '';
                        switch(index){
                            default: text='';  
                            case 0:text='综合';break;
                            case 1:text='活动';break;
                            case 2:text='公告';break;
                        }
                        return '<div class="' + className + '">' + text + '</div>';
                    },
                },
            }
        }; 
    },
    created: function() {
        // console.log('%c announcement created', a);
        let _this = this,
            focus_type = '2,3',
            articleId = '48,50,51',
            artPage = 1,
            artLimit = 5;
        
        request_articleList(articleId, artPage, artLimit)
            .then(function(response) {
                // console.log(response.data);
                _this.announcementList = response.data;
            });
    },
   
}
</script>
<style lang="scss">
#announcement{
    width: 750px;
    height: 825px;
    position: relative;
    .announce-title{
        width: 750px;
        height: 113px;
        background: url('../../assets/ah-notice-title.png') no-repeat;
        background-size: 100% 100%;
    }
    .announce-more{
        position: absolute;
        display: block;
        width: 111px;
        height: 80px;
        line-height: 80px;
        top: 120px;
        right: 25px;
        color: #d3cbe1;
        font-size: 28px;
        z-index: 99;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
    }
    #swiper-content{
        padding-top: 100px;
        width: 700px;
        height: 750;
        a{
            text-decoration: none;
        }
        .swiper-pagination-bullets{
            position: absolute;
            width: 100%;
            height: 80px;
            top: 0px;
            left: 0;
            bottom: 0;
            border-bottom: 2px solid #4c4356;
        }
        .swiper-pagination-bullet{
            width: 111px;
            height: 80px;
            border-radius: 0;
            color: #d3cbe1;
            font-size: 28px;
            text-align: center;
            line-height: 95px;
            background: none;
            margin: 0 70px 0 10px;
            font-weight: 600;
            opacity: 1;
        }
        .swiper-pagination-bullet-active{
            color: #ff350f;
            border-bottom: 5px solid #ff350f;
            background: none;
            font-size: 28px;
        }
    }
    .headline{
        width: 100%;
        height: 190px;
        border-bottom: 1px #4c4356 dashed;
        .headline-img-border{
            width: 234px;
            height: 133px;
            background: url('../../assets/headline-img-border.png') no-repeat;
            background-size: 100% 100%;
            padding: 5px 0 0 8px;
            .headline-img{
                width: 218px;
                height: 116px;
                // background: skyblue;
                // background-image: url('../../assets/headline-image.png');
                background-repeat: no-repeat;
                background-size: 100%;
                background-position: center;
            }
        }
        .headline-content{
            width: 466px;
            padding: 40px 20px 20px;
        }
        .headline-title{
            width: 100%;
            font-size: 28px;
            color: #ff310d;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .headline-text{
            margin: 8px auto;
            font-size: 24px;
            color: #92919e;
            line-height: 34px;
            height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
    }
    .headline-detial{
        display: block;
        position: absolute;
        right: 24px;
        top: 105px;
        font-size: 24px;
        color: #962427;
        text-decoration: none;
    }
    .headline-detial:link{
        text-decoration: underline;
    }
    .announce-list{
        display: flex;
        height: 130px;
        border-bottom: 1px #4c4356 dashed;/* no*/
        padding-top: 25px; 
        >span{
            width: 26px;
            height: 34px;
            background: url('../../assets/announce-list-icon.png') no-repeat;
            background-size: 26px 24px;
            background-position: center;
        }
        >div{
            flex-shrink: 0;
            width: 620px;
        }
        .announce-list-tit{
            font-size: 28px;
            color: #ffffff;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .announce-list-dec{
            margin-top: 5px;;
            font-size: 24px;
            color: #92919e;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            padding-left: 10px;
        }
    }
    // .announce-list:last-child{
    //     border-bottom: none;
    // }
}
</style>
