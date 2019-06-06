<template>
    <div id="game-feature">
        <div class="game-title-bg"></div>
        <div class="game-title"></div>
        <div class="entry-game-slide">
            <swiper :options="swiperOption" ref="mySwiper" id="entry-head-swiper">
                <swiper-slide class="slide-head" v-for="(item ,index) in focus_pictures" :key="index">
                     <a :href="item.jump_url || 'javascript:;' ">
                        <img class="pic" :src="domain + '/' + item.picture" alt="slide">
                    </a>
                </swiper-slide>
                
            </swiper>
            <div class="swiper-button-prev feature-button-prev" slot="button-prev"></div>
            <div class="swiper-button-next feature-button-next" slot="button-next"></div>
        </div>
        <div class="next-slide"></div>
    </div>
</template>
<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
import 'swiper/dist/css/swiper.css'
import { request_focus_picture } from '../../service/ajax.js'

export default {
    name: 'gameFeature',
    components: {
        swiper,
        swiperSlide
    },
    data() {
        return {
            domain: this.$store.state.domain,
            focus_pictures: [],
            swiperOption: {
                // notNextTick是一个组件自有属性，如果notNextTick设置为true，组件则不会通过NextTick来实例化swiper，也就意味着你可以在第一时间获取到swiper对象，假如你需要刚加载遍使用获取swiper对象来做什么事，那么这个属性一定要是true
                notNextTick: true,
                loop: false,
                paginationClickable: true,
                 navigation: {
                    nextEl: '.feature-button-next',
                    prevEl: '.feature-button-prev',
                },
                initialSlide: 1,
                effect: 'coverflow',
                slidesPerView: 2,
                centeredSlides: true,
                loop : true,
                coverflow: {
                    rotate: 30,
                    stretch: 30,
                    depth: 100,
                    modifier: 1,
                    slideShadows : true
                },
            }
        };
    },
    mounted: function() {
        let _this = this, focus_type = 1;
        
        request_focus_picture(focus_type)
            .then(function(response) {
                _this.focus_pictures = response.data.data[focus_type];
            });
    }
}
</script>
<style lang="scss">
#game-feature{
    position: relative;
    width: 750px;
    height: 1209px;
    background: url('../../assets/entry-publish-welfare.jpg') no-repeat;
    background-size: 100% 100%;
    overflow: hidden;
    .game-title-bg{
        width: 750px;
        height: 230px;
        background: url('../../assets/entry-game-title.png') no-repeat;
        background-size: 100% 100%;
    }
    .game-title{
        position: absolute;
        top: 175px;
        left: 15px;
        width: 720px;
        height: 61px;
        background: url('../../assets/entry-game-feature-title.png') no-repeat;
        background-size: 100% 100%;
    }
    .entry-game-slide{
        margin: 90px auto 0;
        text-align: center;
        .feature-button-prev{
            width: 51px;
            height: 69px;
            background: url('../../assets/arrow_left.png') no-repeat;
            background-size: 100% 100%;
            left: 15px;
            margin-top: 60px;
        }
        .feature-button-next{
            width: 51px;
            height: 69px;
            background: url('../../assets/arrow_right.png') no-repeat;
            background-size: 100% 100%;
            right: 15px;
            margin-top: 60px;
        }
    }
    #entry-head-swiper{
        margin: 0 auto;
        width: 575px;
        height: 780px;
        img{
            width: 438px;
            height: 778px;
            margin-left: -80px; 
        }
    }
    .next-slide{
        margin: 50px auto 0;
        width: 87px;
        height: 40px;
        background: url('../../assets/arrow-down.png') no-repeat;
        background-size: 100% 100%;
    }
}
</style>
