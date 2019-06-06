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
    // created() {
    //     alert(document.documentElement.style.fontSize)
    //     alert('w='+window.screen.width+' '+ 'h='+window.screen.height);
    // },
    // mounted() {
    //     this.$nextTick(() => {
    //         let dom = document.getElementById('index');
    //         let h = dom.offsetHeight;
    //         let w = dom.offsetWidth;

    //         alert('h='+ h + ' ' +'w=' + w);
    //     })
    // }
}
</script>
<style lang="scss">
#index{
    width: 10rem;
    height: 1708px;
    margin: -853px 0 0 -480px;
    position: absolute;
    top: 50%;
    left: 50%;
    background: url('../assets/bg.png') no-repeat;
    background-size: 960px 1708px;
}
</style>
