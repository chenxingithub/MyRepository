<template>
    <div id="home-page">
        <head-nav></head-nav>
        <head-slide :focus_list="focus_picture"></head-slide>
        <div id="home-box">
            <home-content-part1></home-content-part1>
            <home-content-part2 :newsList="news_list"></home-content-part2>
            <friendly-link></friendly-link>
        </div>
        <foot></foot>
    </div>
</template>
<script>
import headNav from '../../components/head/index.vue'
import foot from '../../components/foot/index'
// import headSlide from "./headSlide"
import headSlide from "./headSlide1"
import homeContentPart1 from './home-con-pt1'
import homeContentPart2 from './home-con-pt2'
import friendlyLink from '../../components/friendlyLink/index'
import {
    request_focus_picture,
    request_articleList
    } from '../../api/index.js'

export default {
    name: "homePage",
    data() {
        return {
            focus_picture: null, // 首页背景焦点图
            news_list: null //最新资讯
        };
    },
    components: {
        headSlide,
        homeContentPart1,
        homeContentPart2,
        friendlyLink,
        headNav,
        foot
    },
    created() {
        let focus_pic_id = 1, //首页背景焦点
            news_list_id = 71,//最新资讯
            news_list_page = 1,
            news_list_limit = 6;

        request_focus_picture(focus_pic_id).then(resolve => {
            this.focus_picture = resolve.data.data[focus_pic_id];
        });

        request_articleList(news_list_id, news_list_page, news_list_limit).then(resolve => {
            this.news_list = resolve.data[news_list_id];
        });
    }
}
</script>
<style lang="scss">
#home-page{
    position: relative;
    zoom: 1;
    overflow: hidden;
    #home-box{
        // padding: 0 20px;
        position: relative;
        z-index: 1;
        top: 588px;
        width: 1200px;
        opacity: 1;
        // height: auto;
        // margin: 0 auto 588px;
        margin: 0 auto 588px;
    }
}

</style>
