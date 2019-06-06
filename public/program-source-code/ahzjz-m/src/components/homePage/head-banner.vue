<template>
    <div id="head-banner">
        <swiper :options="swiperOption" ref="mySwiper" id="head-swiper">
            <swiper-slide class="slide-head" v-for="(item, index) in headFocus" :key="index">
                <a :href="item.jump_url || 'javascript:;'">
                    <img class="slide-img" :src="domain + '/' + item.picture" alt="slide">
                </a>
            </swiper-slide>
            <div class="swiper-pagination"  slot="pagination"></div>
        </swiper>
        <div class="header-bg"></div>
    </div>
</template>
<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
import 'swiper/dist/css/swiper.css'


export default {
    name: 'headBanner',
    components: {
        swiper,
        swiperSlide
    },
    props: ['headFocus'],
    mounted() {
        
    },
    data() {
        return {
            domain: this.$store.state.domain,
            swiperOption: {
                // notNextTick是一个组件自有属性，如果notNextTick设置为true，组件则不会通过NextTick来实例化swiper，也就意味着你可以在第一时间获取到swiper对象，假如你需要刚加载遍使用获取swiper对象来做什么事，那么这个属性一定要是true
                notNextTick: true,
                // swiper configs 所有的配置同swiper官方api配置
                autoplay: {
                    delay: 2000000,
                    stopOnLastSlide: false,
                    disableOnInteraction: false,
                },
                loop : true,
                direction : 'horizontal',
                prevButton:'.swiper-button-prev',
                nextButton:'.swiper-button-next',
                pagination: {
                    el: '.swiper-pagination'
                },
            }
        }
    }
}
</script>
<style lang="scss">
#head-banner{
    height: 400px;
    position: relative;
    .swiper-container{
        overflow: hidden;
    }
    #head-swiper{
        color: #ffffff;
        text-align: center;
        height: 400px;
        .swiper-slide{
            height: 320px;
        }
        .slide-img{
            // width: 750px;
            height: 320px;
        }
        .swiper-pagination{
            z-index: 99;
            top: 355px;
            left: 10px;
        }
        .swiper-pagination-bullet{
            width: 20px;
            height: 20px;
            background: #b9b0cd;
            opacity: 1;
            margin: 0 7px;
        }
        .swiper-pagination-bullet-active{
            background: #ff190c;
        }
    }
    .header-bg{
        position: absolute;
        top: 318px;
        left: 0;
        width: 750px;
        height: 21px;
        background: url('../../assets/header-bg.png') no-repeat;
        background-size: 100% 100%;
    }
}
</style>
