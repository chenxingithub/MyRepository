<template>
  <div class="left-nav">
    <div class="icon">
      <div class="merman-baby"></div>
    </div>
    <div class="nav-box">
      <router-link :to="{path: '/hotspot'}"
        :class="['hotspot', 'item', selected == 'hotspot' ? 'item-act' : false]">
        热&nbsp;点
      </router-link>
      <router-link :to="{path: '/recommend'}"
        :class="['recommend', 'item', selected == 'recommend' ? 'item-act' : false]">
        推&nbsp;荐
      </router-link>
      <router-link :to="{path: '/feedback'}"
        :class="['feedback', 'item', selected == 'feedback' ? 'item-act' : false]">
        反&nbsp;馈
        <div class="message" v-show="feedBackMsg == 'show'"></div>
      </router-link>
    </div>
  </div>
</template>
<script>
import { mapState, mapMutations } from 'vuex'
import { feedbackMsgStatusQuery } from '../../api/index.js'
export default {
  name: 'leftNav',
  data() {
    return {
      selected: 'hotspot'
    }
  },
  computed: {
    ...mapState(['feedBackMsg','urlParams'])
  },
  methods: {
    ...mapMutations(['handleFeedBackMsgStatus']),
    exchangeNav() {

      switch(this.$route.name) {
        case 'hotspot':
          this.selected = 'hotspot';
          break;
        case 'recommend':
          this.selected = 'recommend';
          break;
        case 'feedback':
          this.selected = 'feedback';
          break;
        // default :
        //   this.selected = 'hotspot';
      }
    },
  },
  created() {
    feedbackMsgStatusQuery(this.urlParams.role_id)
      .then(res => {
        let data = res.data;
        if(data.state == 0 && data.number > 0) {
          this.handleFeedBackMsgStatus('show');
        }
      })
  },
  watch: {
    "$route": "exchangeNav"
  }
}
</script>
<style lang="scss">
.left-nav{
  width: 300px;
  height: 645px;
  background: url('../../assets/common/nav-bg.png') no-repeat;
  background-size: 300px 645px;
  .icon{
    width: 100%;
    height: 360px;
    padding-top: 39px;
    background: url('../../assets/common/merman-baby-box.png') no-repeat;
    background-size: 257px 319px;
    background-position: center 45px;
  }
  .merman-baby{
    width: 220px;
    height: 280px;
    border-radius: 0 0 220px 220px;
    background: url('../../assets/common/merman-baby.gif') no-repeat;
    background-size: 220px 280px;
    margin: 0 auto;
  }
  .item{
    display: block;
    text-align: center;
    margin: 10px auto 10px;
    width: 225px;
    height: 54px;
    line-height: 58px;
    background: url('../../assets/common/nav-btn-bg.png');
    background-repeat: no-repeat;
    background-size: 225px 54px;
    color: #ffffff;
    font-size: 24px;
    text-decoration: none;
    position: relative;
  }
  .item-act{
    color: #eae4c2;
    background-image: url('../../assets/common/nav-btn-act-bg.png');
  }
  .message{
    position: absolute;
    top: -8px;
    right: -6px;
    $w: 24px;
    width: $w;
    height: $w;
    background: url('../../assets/common/msg-flag.png') no-repeat;
    background-size: $w $w;
  }
}
</style>
