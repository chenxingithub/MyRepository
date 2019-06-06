<template>
  <div id="app">
    <router-view>
    </router-view>
  </div>
</template>

<script>
import * as http from './service/ajax.js';
import { isWeiXin, judgeClientOs } from './utils/index.js'
import { mapState, mapMutations, mapActions } from 'vuex';

export default {
  name: 'App',
  data() {
      return {
          downloadLink: ''
      }
  },
  created() {
        let _this = this;
        let os = judgeClientOs();
        let _isWeiXin = isWeiXin();

        http.request_base_info()
            .then(function(response){
                _this.baseInfo = response.data.data[0];
                _this.saveBaseInfo(response.data.data[0]);
            });
        this.saveOs(os);
        this.saveIsWeiXin(_isWeiXin);
  },
  methods: {
        ...mapMutations([
            'saveBaseInfo',
            'saveOs',
            'saveIsWeiXin'
        ]),
        ...mapState([
            'baseInfo'
        ])
  }
}
</script>

<style>
@import './style/base.css';
#app {
  margin: 0 auto;
  width: 750px;
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  /* background: #2c3e50; */
}
</style>
