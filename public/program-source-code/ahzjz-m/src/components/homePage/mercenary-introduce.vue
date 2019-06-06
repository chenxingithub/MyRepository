<template>
    <div id="mercenaryIntru">
        <div class="mercenary-title"></div>
        <div class="mercenary-content">
            <swiper :options="mercenary_swiperOption" ref="mercenarySwipe" class="swiper-content">

                <swiper-slide v-for="(item, index) in mercenaryFocus" :key="index">
                    <a :href="item.jump_url || 'javascript:;'">
                        <img src="../../assets/mercenary-bg.png" alt="slide">
                        <div class="mercenary-pic">
                            <img :src="domain +'/'+ item.picture" alt="slide">
                        </div>
                    </a>
                </swiper-slide>
                
                <div class="mercenary-pagination"  slot="pagination"></div>
            </swiper>
        </div>
    </div>
</template>
<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper';
import 'swiper/dist/css/swiper.css';

let a = "background: #EE7AE9;color: #ffffff;font-size: 14px;"

export default {
    name: 'mercenaryIntroduce',
    components: {
        swiper,
        swiperSlide
    },
    props: ['mercenaryFocus'],
    data() {
        return {
            domain: this.$store.state.domain,
            mercenary_swiperOption: {
                // autoplay:true,//等同于以下设置
                loop: false,
                initialSlide: 1,
                effect: 'coverflow',
                slidesPerView: 3,
                centeredSlides: true,
                coverflowEffect: {
                    rotate: 0,
                    stretch: -50,
                    depth: 180,
                    modifier: 1,
                    slideShadows : false
                },
                pagination: {
                    el: '.mercenary-pagination',
                    // bulletElement : 'li',
                }
            }
        };
    },
    computed: {
        swiper() {
            return this.$refs.mercenarySwipe.swiper
        }
    },
}
</script>
<style lang="scss">
#mercenaryIntru{
    width: 100%;
    height: 790px;
    position: relative;
    .mercenary-title{
        position: relative;
        width: 750px;
        height: 113px;
        background: url('../../assets/mercenary-introduce-title.png') no-repeat;
        background-size: 100% 100%;
    }
    .mercenary-content{
        width: 750px;
        height: 620px;
        background: url('../../assets/mercenaryIntru.jpg') no-repeat;
        background-size: 100% 100%;
        z-index: -1;
    }
    .swiper-content{
        width: 680px;
        height: 540px;
        margin-top: 80px;
        .swiper-slide{
            width: 700px;
            height: 465px;
            text-align: center;
            .mercenary-pic{
                position: absolute;
                top: 6px;
                left: 16px;
                width: 200px;
                height: 450px;
                overflow: hidden;
                // background-image: url('../../assets/mercenary-1.png');
                // background-size: auto 390px;
                // background-repeat: no-repeat;
                // background-position: bottom center;
                >img{
                    position: absolute;
                    bottom: 0;
                    left: 50%;
                    height: 390px;
                    width: auto;
                    transform: translateX(-50%);
                }
            }
            img{
                width: 230px;
                height: 465px;
            }
        }
        .swiper-slide-active{
            .mercenary-pic{
                overflow: visible !important;
            }
        }
        .swiper-pagination-bullets{
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
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
}
</style>
