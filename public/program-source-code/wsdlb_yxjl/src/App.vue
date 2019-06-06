<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import { panelListReq } from './api/request.js';
export default {
  name: 'App',
  computed: {
        ...mapState([
            'baseConf',
            'urlParams'
        ]),
        // ...mapMutations({
        //   saveConf: 'saveBaseConf'
        // })
  },
  created() {
        let _this = this;
        panelListReq(this.urlParams.game_id).then(function(res) {
                
                _this.$store.commit('saveBaseConf', res.data.data);
            });
    }
}
</script>

<style>
@import './styles/base.css';
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>
