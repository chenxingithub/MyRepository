<template>
  <div class="mercenaries">
    <div class="mercenaries-con clearfix">
      <div class="mercenaries-box-1 clearfix" v-if="mercenaries_lists">
        <div class="item-box-1">
          <div class="item-tit-tip"></div>
          <router-link :to="{path: '/article' ,query: {articleId: mer_lists[0].id}}" tag="div" class="item-0 item">
            <div class="item-cover"></div><!-- 天使军 -->
            <img src="../../assets/images/mercenaries-0.png" alt="image">
          </router-link>
          <router-link :to="{path: '/article' ,query: {articleId: mer_lists[2].id}}" tag="div" class="item-1 item">
            <div class="item-cover"></div><!-- 圣骑士 -->
            <img src="../../assets/images/mercenaries-1.png" alt="image">
          </router-link>
          <router-link :to="{path: '/article' ,query: {articleId: mer_lists[3].id}}" tag="div" class="item-2 item">
            <img src="../../assets/images/mercenaries-2.png" alt="image">
            <div class="item-cover"></div><!-- 亚马逊-->
          </router-link>
        </div>
        <div class="item-box-2">
          <router-link :to="{path: '/article' ,query: {articleId: mer_lists[1].id}}" tag="div" class="item-3 item">
            <img src="../../assets/images/mercenaries-3.png" alt="image">
            <div class="item-cover"></div><!-- 刺客 -->
          </router-link>
        </div>
      </div>
      <div class="mercenaries-box-2 clearfix" v-if="mercenaries_lists">
        <router-link :to="{path: '/article' ,query: {articleId: mer_lists[4].id}}" tag="div" class="item-4 item">
          <img src="../../assets/images/mercenaries-4.png" alt="image">
          <div class="item-cover"></div><!-- 德鲁伊-->
        </router-link>
        <router-link :to="{path: '/article' ,query: {articleId: mer_lists[5].id}}" tag="div" class="item-5 item">
          <img src="../../assets/images/mercenaries-5.png" alt="image">
          <div class="item-cover"></div><!-- 亡灵法师 -->
        </router-link>
      </div>
    </div>
    <div class="arrow-next" @click="$emit('slideNext')"></div>
  </div>
</template>
<script>
export default {
  name: 'mercenaries',
  props: [
    'mercenaries_lists',
    'mercenaries_ids'
  ],
  data() {
    return {
      id: [292,293,294,295,296,297]//天使军，刺客，圣骑士，亚马逊，德鲁伊，亡灵法师
    }
  },
  computed: {
    mer_lists() {
      if(!this.mercenaries_lists) { return false; }

      return this.mercenaries_lists[this.mercenaries_ids[4]].data[0].data.reverse();
    }
  }
}
</script>
<style lang="scss">
.mercenaries{
  width: 100%;
  height: 1080px;
  position: relative;
  @include bg('../../assets/images/mercenary-introduce-bg.jpg', 1920px, 1080px, center, center);
  .arrow-next{
    width: 67px;
    height: 45px;
    position: absolute;
    bottom: 140px;
    left: 50%;
    margin-left: -34px;
    z-index: 100;
    cursor: pointer;
    @include bg('../../assets/images/arrow-down.png');
  }
  .mercenaries-con{
    width: 1200px;
    height: 810px;
    margin: 0 auto;
    // background: rgba(130, 236, 255, 0.45);
    position: relative;
    top: 135px;
  }

  .item{
    position: relative;
    cursor: pointer;
    transition: background 0.35s ease-in;
    >img{
      position: absolute;
      top: 0;
      left: 0;
      z-index: 2;
    }
    .item-cover{
      width: 100%;
      height: 100%;
      background: rgba(0,0,0, .75);
      transition: background 0.3s linear;
      position: absolute;
      top: 0;
      left: 0;
      z-index: 1;
    }
  }
  .item:hover{
    .item-cover{
      background: rgba(0,0,0, 0);
      transition: background 0.3s linear;
    }
    background-size: 105% 105%;
    transition: background 0.35s ease-out;
  }

}
.item-box-1{
    height: 530px;
    width: 900px;
    // background: rgba(121, 245, 234, 0.45);
    float: left;
    >div{
      float: left;
    }
    .item-tit-tip{
      width: 136px;
      height: 150px;
      @include bg('../../assets/images/MORE.png', 57px, 150px);
    }
    .item-0{
      width: 764px;
      height: 190px;
      margin-top: 38px;
      @include bg('../../assets/images/mercenaries-bg-0.png');
      >img{
        margin: -36px 0 0 0;
      }
    }
    .item-1{
      width: 502px;
      height: 292px;
      margin-top: 4px;
      @include bg('../../assets/images/mercenaries-bg-1.png', 502px, 292px, center, center);
      >img{
        margin: 17px 0 0 -156px;
      }
    }
    .item-2{
      width: 397px;
      height: 291px;
      margin: 4px 0 0 1px;
      @include bg('../../assets/images/mercenaries-bg-2.png', 397px, 291px, center, center);
      >img{
        margin: 22px 0 0 3px;
      }
    }
  }
  .item-box-2{
    height: 530px;
    width: 295px;

    // background: rgba(166, 240, 116, 0.45);
    float: right;
    .item-3{
      width: 295px;
      height: 487px;
      overflow: hidden;
      margin-top: 38px;
      @include bg('../../assets/images/mercenaries-bg-3.png', 295px, 487px, center, center);
      >img{
        margin: 28px 0 0 32px;
      }
    }
  }
  .mercenaries-box-2{
    width: 1200px;
    height: 290px;
    // background: rgba(245, 70, 57, 0.45);
    >div{
      height: 270px;
    }
    .item-4{
      width: 610px;
      // background: rgba(72, 245, 57, 0.45);
      float: left;
       @include bg('../../assets/images/mercenaries-bg-4.png', 610px, 270px, center, center);
      >img{
        margin: 12px 0 0 32px;
      }
    }
    .item-5{
      width: 585px;
      // background: rgba(191, 111, 238, 0.45);
      height: 270px;
      float: right;
      overflow: hidden;
       @include bg('../../assets/images/mercenaries-bg-5.png', 585px, 270px, center, center);
      >img{
        margin: 9px 0 0 25px;
      }
    }

}
</style>
