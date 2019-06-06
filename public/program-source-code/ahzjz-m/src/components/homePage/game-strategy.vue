<template>
    <div id="game-strategy">
        <div class="strategy-title"></div>
        <div class="strategy-content">
            <router-link :to="{path: '/listNav', query: {type: 'gameStrategy', id: 49}}" class="announce-more">更多＋</router-link>
            <div class="strategy-swiper-pagination"  slot="pagination"></div>
            <div class="strategy-head flex-sb-c" v-if="gameStrategyList">
                <router-link class="strategy-head-l" 
                v-if="!gameStrategyList['54'].data[0].data[1].jump_url"
                :to="{path: '/articledetails', query: {id: gameStrategyList['54'].data[0].data[1].id} }">
                    <img :src="domain +'/'+ gameStrategyList['54'].data[0].data[1].icon" alt="image">
                    <div class="strategy-head-text">
                        <p>{{ gameStrategyList['54'].data[0].data[1].title }}</p>
                        <span>{{ gameStrategyList['54'].data[0].data[1].subtitled }}</span>
                    </div>
                </router-link>
                <a class="strategy-head-l" 
                v-if="gameStrategyList['54'].data[0].data[1].jump_url"
                :href="gameStrategyList['54'].data[0].data[1].jump_url">
                    <img :src="domain +'/'+ gameStrategyList['54'].data[0].data[1].icon" alt="image">
                    <div class="strategy-head-text">
                        <p>{{ gameStrategyList['54'].data[0].data[1].title }}</p>
                        <span>{{ gameStrategyList['54'].data[0].data[1].subtitled }}</span>
                    </div>
                </a>

                <router-link class="strategy-head-r" 
                v-if="!gameStrategyList['54'].data[0].data[0].jump_url"
                :to="{path: '/articledetails', query: {id: gameStrategyList['54'].data[0].data[0].id} }">
                    <img :src="domain +'/'+ gameStrategyList['54'].data[0].data[0].icon" alt="image">
                    <div class="strategy-head-text">
                        <p>{{ gameStrategyList['54'].data[0].data[0].title }}</p>
                        <span>{{ gameStrategyList['54'].data[0].data[0].subtitled }}</span>
                    </div>
                </router-link>
                <a class="strategy-head-r" 
                v-if="gameStrategyList['54'].data[0].data[0].jump_url"
                :href="gameStrategyList['54'].data[0].data[0].jump_url" >
                    <img :src="domain +'/'+ gameStrategyList['54'].data[0].data[0].icon" alt="image">
                    <div class="strategy-head-text">
                        <p>{{ gameStrategyList['54'].data[0].data[0].title }}</p>
                        <span>{{ gameStrategyList['54'].data[0].data[0].subtitled }}</span>
                    </div>
                </a>
                <!-- <router-link class="strategy-head-r" 
                :to="{path: '/articledetails', query: {id: gameStrategyList['54'].data[0].data[0].id} }">
                    <img :src="domain +'/'+ gameStrategyList['54'].data[0].data[0].icon" alt="image">
                    <div class="strategy-head-text">
                        <p>{{ gameStrategyList['54'].data[0].data[0].title }}</p>
                        <span>{{ gameStrategyList['54'].data[0].data[0].subtitled }}</span>
                    </div>
                </router-link> -->
            </div>
            <swiper :options="strategy_swiper" class="strategy-swiper">
                <!-- <div class="strategy-swiper-pagination"  slot="pagination"></div> -->
                <!-- <swiper-slide>
                    <div class="strategy-head flex-sb-c">
                        <div class="strategy-head-l">
                            <div class="strategy-head-l-img"></div>
                        </div>
                        <div class="strategy-head-r">
                            <div class="strategy-head-r-img"></div>
                        </div>
                    </div>
                    <a href="javascript:;">
                        <div class="strategy-list">
                            <span></span>
                            <div>
                                <p class="strategy-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                                <p class="strategy-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                            </div>
                        </div>
                    </a>
                    <a href="javascript:;">
                        <div class="strategy-list">
                            <span></span>
                            <div>
                                <p class="strategy-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                                <p class="strategy-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                            </div>
                        </div>
                    </a>
                    <a href="javascript:;">
                        <div class="strategy-list">
                            <span></span>
                            <div>
                                <p class="strategy-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                                <p class="strategy-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                            </div>
                        </div>
                    </a>
                </swiper-slide>
                <swiper-slide>
                    <div class="strategy-head flex-sb-c">
                        <div class="strategy-head-l">
                            <div class="strategy-head-l-img"></div>
                        </div>
                        <div class="strategy-head-r">
                            <div class="strategy-head-r-img"></div>
                        </div>
                    </div>
                    <a href="javascript:;">
                        <div class="strategy-list">
                            <span></span>
                            <div>
                                <p class="strategy-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                                <p class="strategy-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                            </div>
                        </div>
                    </a>
                    <a href="javascript:;">
                        <div class="strategy-list">
                            <span></span>
                            <div>
                                <p class="strategy-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                                <p class="strategy-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                            </div>
                        </div>
                    </a>
                    <a href="javascript:;">
                        <div class="strategy-list">
                            <span></span>
                            <div>
                                <p class="strategy-list-tit">【暗黑终结者】 安卓635服“米尔岗口”17日10:00火爆开启！17日10:00火爆开启！</p>
                                <p class="strategy-list-dec">安卓635服“米尔岗口”17日10:00火爆开启！欢迎新老玩家进驻~！</p>
                            </div>
                        </div>
                    </a>
                </swiper-slide> -->
                <swiper-slide v-for="(items, index) in gameStrategyList" :key="index" v-if="index < 54">
                    <a href="javascript:;" style="text-decoration:none;" v-for="(item, index) in items.data[0].data" :key="index">
                        <router-link tag="div" class="strategy-list" :to="{path: '/articledetails', query: {id: item.id} }">
                            <span></span>
                            <div>
                                <p class="strategy-list-tit">【{{ items.name[0] == "游戏攻略" ? '综合' : items.name[0] }}】{{ item.title }}</p>
                                <p class="strategy-list-dec">{{ item.subtitled }}</p>
                            </div>
                        </router-link>
                    </a>
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>
<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper';
import 'swiper/dist/css/swiper.css';
import { request_articleList } from '../../service/ajax.js'

export default {
    name: 'gameStrategy',
    components: {
        swiper,
        swiperSlide
    },
    data() {
        return {
            domain: this.$store.state.domain,
            gameStrategyList: null,
            strategy_swiper: {
                pagination: {
                    el: '.strategy-swiper-pagination',
                    clickable: true,
                    renderBullet: function (index, className) {
                        let text = '';
                        switch(index){
                            default: text='';  
                            case 0:text='综合';break;
                            case 1:text='新手';break;
                            case 2:text='进阶';break;
                        }
                        return '<div class="' + className + '">' + text + '</div>';
                    },
                },
            }
        };
    },
    mounted: function() {
        let _this = this,
            focus_type = '2,3',
            articleId = '49,52,53,54',
            artPage = 1,
            artLimit = 3;

        request_articleList(articleId, artPage, artLimit)
            .then(function(response) {
                // console.log(response.data);
                _this.gameStrategyList = response.data;
            });
    }
}
</script>
<style lang="scss">
#game-strategy{
    width: 750px;
    height: 700px;
    .strategy-title{
        width: 750px;
        height: 113px;
        background: url('../../assets/game-strategy-title.png') no-repeat;
        background-size: 100% 100%;
        position: relative;
    }
    .strategy-content{
        padding: 100px 20px 0;
        position: relative;
        .announce-more{
            position: absolute;
            display: block;
            width: 111px;
            height: 80px;
            line-height: 80px;
            top: 20px;
            right: 20px;
            color: #d3cbe1;
            font-size: 28px;
            z-index: 99;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
        }
        .strategy-head{
            padding: 0 20px;
            margin: 40px auto 20px;
            >a{
                display: block;
                text-decoration: none;
                width: 296px;
                height: 169px;
                position: relative;
                background: url('../../assets/strategy-img-border.png') no-repeat;
                background-size: 100% 100%;
                img{
                    width: 100px;
                    position: absolute;
                    top: 15px;
                    left: 0;
                }
            }
            .strategy-head-text{
               position: absolute;
               top: 0;
               right: 0;
               width: 220px;
               height: 144px;
               color: #ffffff;
               text-align: center;
               
               p{
                   font-size: 36px;
                   margin: 35px auto 5px;
               }
               span{
                   font-size: 20px;
                   
               }
            }
        }
    }
    .strategy-list{
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
        .strategy-list-tit{
            font-size: 28px;
            color: #ffffff;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .strategy-list-dec{
            margin-top: 5px;;
            font-size: 24px;
            color: #92919e;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            padding-left: 10px;
        }
    }

    $height: 80px;

    // .strategy-swiper{
    //     padding-top: $height;
    // }
    .swiper-pagination-bullets{
        position: absolute;
        bottom: 0;
        top: 20px;
        width: 710px;
        height: $height;
        border-bottom: 1px solid #4c4356;/*no*/
    }
    .swiper-pagination-bullet{
        width: 111px;
        height: $height;
        opacity: 1;
        background: none;
        border-radius: 0;
        text-align: center;
        line-height: $height;
        font-weight: 600;
        font-size: 28px;
        margin: 0 70px 0 10px;
        color: #d3cbe1;
    }
    .swiper-pagination-bullet-active{
        color: #ff350f;
        font-size: 28px;
        border-bottom: 5px solid #ff350f;
        
    }
}
</style>
