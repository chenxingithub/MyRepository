<template>
    <div id="index">
        <head-nav></head-nav>
        <router-view></router-view>
        <foot-nav></foot-nav>
    </div>
</template>
<script>

import headNav from '../components/Head/Head';
import footNav from '../components/Foot/Foot';
import { GetQueryString } from '../utils/utils.js'

export default {
    name: 'index',
    components: {
        headNav,
        footNav
    },
    created() {
        // alert(document.documentElement.style.fontSize);
        console.log('手机屏幕w='+window.screen.width+' '+ 'h='+window.screen.height);
        console.log('浏览器大小w='+document.documentElement.clientWidth+' '+ 'h='+document.documentElement.clientHeight);
    },
    mounted() {
        this.$nextTick(() => {
            let dom = document.getElementById('index');
            let h = dom.offsetHeight;
            let w = dom.offsetWidth;

            console.log('游戏精灵w='+ w + ' ' +'h=' + h);
        })
    }
}
</script>
<style lang="scss">
#index{
    width: 900px;
    height: 1600px;
    margin: -800px 0 0 -450px;
    position: absolute;
    top: 50%;
    left: 50%;
    background: url('../assets/bg.png') no-repeat;
    background-size: 900px 1600px;
}
</style>
