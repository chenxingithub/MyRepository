<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
  import {
    mapState,
    mapMutations
  } from 'vuex'
  import {
    panelListReq,
    baseInfoReq
  } from './api/index.js';

  export default {
    name: 'App',
    methods: {
      ...mapMutations({
        saveConf: 'saveBaseConf',
        saveBaseInfo: 'handleSaveBaseInfo'
      })
    },
    computed: {
      ...mapState([
        'baseConf',
        'urlParams'
      ])
    },
    created() {
      let _this = this;
      panelListReq(this.urlParams.game_id).then(function (res) {

        _this.$store.commit('saveBaseConf', res.data.data);
      });
      baseInfoReq(this.urlParams.game_id, this.urlParams.channel_id).then(res => {

        // _this.baseInfo = res.data.data;
        this.saveBaseInfo(res.data.data);
      });
    }
  }

</script>

<style lang='scss'>
  @import './styles/base.css';

  html,
  body {
    height: 100%;
    width: 100%;
    ;
    background: rgba(0, 0, 0, 0);
    margin: 0;
    padding: 0;
  }

  #app {
    width: 100%;
    height: 100%;
    /* background: rgba(0,0,0,0); */
    /* background: #74e9f1; */
    font-family: 'Microsoft Yahei';
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
  }

</style>
