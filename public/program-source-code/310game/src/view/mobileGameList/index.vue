<template>
    <div id="mobile-game-list">
      <head-nav></head-nav>
        <div class="game-list-head">
            <div class="list-head-bg">
                <!-- 预留放视屏， 暂时图片  -->
            </div>
        </div>
        <div class="game-list-con clearfix" v-if="gameLists">
            <div class="game-list-item border-box" @mouseleave="handleMouseOut"
              v-for="(item, index) in gameLists.data[0].data" :key="index">
                <div class="flag" :style="{'background-image': 'url('+ handleFlag(item.label) +')'}"></div>
                <div class="game-list-hover">
                  <div class="gameQrcode" v-show="item.enclosure_url">
                    <div :id="'qrcode-'+ index"></div>
                    <p>扫一扫下载游戏</p>
                  </div>
                  <div class="bn-wx-qrcode" v-show="!item.enclosure_url"></div>
                </div>
                <div @mouseenter="handleMouseEnter">
                  <img class="list-icon" :src="item.icon ? imgDomain + '/' + item.icon : ''" alt="game">
                  <span class="list-tit">
                      <b>{{ item.title }}</b>
                  </span>
                  <p>
                      <span class="list-type">{{ item.subtitled }}</span>
                      <span class="download-num">下载：{{item.traffic_volume}}</span>
                  </p>
                </div>
                <a :href="handleDownloadUrl(item.enclosure_url)" target="_blank" class="download-btn">
                    立即下载
                </a>
            </div>
            <!-- <div class="game-list-item border-box">
                <div class="game-list-hover"></div>
                <img class="list-icon" src="../../assets/game-list-icon1.png" alt="game">
                <span class="list-tit">
                    <b>仙魔决</b>
                </span>
                <p>
                    <span class="list-type">仙侠情缘</span>
                    <span class="download-num">下载：130万次</span>
                </p>
                <div class="download-btn">
                    立即下载
                </div>
            </div>-->

        </div>
        <el-pagination
            background
            next-text="下一页"
            layout="prev, pager, next, total"
            :page-size = limit
            :total = total
            @current-change="handleRequestList">
        </el-pagination>
        <friendly-link></friendly-link>
        <foot></foot>
    </div>
</template>
<script>
import friendlyLink from '../../components/friendlyLink/index'
import headNav from '../../components/head/index.vue'
import foot from '../../components/foot/index'
import { request_articleList } from '../../api/index.js'
import { mapState } from 'vuex'
import QRCode from 'qrcodejs2'

export default {
    name: 'mobileGameList',
    data() {
      return {
        id: 68,//游戏列表id(后台)
        page: 1,
        limit: 8,
        total: 0,
        gameLists: null
      }
    },
    computed: {
      ...mapState([
        'imgDomain',
        'localImgUrl'
        ])
    },
    methods:{
      handleRequestList(page) {
         request_articleList(this.id, page, this.limit)
          .then((res => {
            let data = res.data[this.id].data[0];
            this.gameLists = res.data[this.id];
            this.page += 1;
            this.limit = data.per_page - 0;
            this.total = data.total - 0;
          }));
      },
      handleMouseEnter(e) {
        let parent = e.currentTarget.parentNode;
        let classList = parent.getAttribute('class');

        parent.setAttribute('class', classList + " game-list-item-hover");
      },
      handleMouseOut(e) {
        let node = e.currentTarget;
        let classList = node.getAttribute('class');

        classList = classList.replace(/game-list-item-hover/, '');
        node.setAttribute('class', classList.trim());
      },
      handleFlag(type) {

        switch (type) {
          case '火爆':
            return this.localImgUrl + 'static/hot-flag.png';
            break;
          case '最新':
            return this.localImgUrl + 'static/new-flag.png';
            break;
          case '推荐':
            return this.localImgUrl + 'static/recommend-flag.png';
            break;
          default:
            return '';
            break;
        }
      },
      handleDownloadUrl(url) {
        return url || "javascript:alert('敬请期待！');";
      },
      qrcode () { //生成二维码
        if(!this.gameLists) {
            return false;
        }
        this.$nextTick (() => {// 节点渲染结束后添加生成二维码

            for(let [index, val] of Object.entries(this.gameLists.data[0].data)) {
                let qrcode = new QRCode('qrcode-'+index, {
                    width: 100,  // 设置宽度
                    height: 100, // 设置高度
                    text: val.enclosure_url //附件链接 -> 下载链接
                });
            }
            // for(let [index, val] of Object.entries(this.$refs.gameListItem)) {
            //     val.style.transform = 'scale(1)';
            //     log( val.style.transform);
            // }
        });
      },
    },
    components: {
        friendlyLink,
        headNav,
        foot
    },
    created() {
      this.handleRequestList(this.page);
    },
    watch: {
        'gameLists': 'qrcode'
    }
}
</script>
<style lang="scss">
#mobile-game-list{
    width: 100%;
    .game-list-head{
        width: 100%;
        min-width: 1200px;
        height: 526px;
        .list-head-bg{
            width: 100%;
            height: 526px;
            min-width: 1200px;
            margin: 0 auto;
            background: url('../../assets/game-list-head.png') no-repeat;
            background-size: cover;
            background-position: center top;
        }
    }
    .game-list-con{
        width: 1200px;
        min-height: 390px;
        margin: 59px auto 69px;
        .game-list-item{
            position: relative;
            width: 280px;
            height: 300px;
            border: 1px solid #dfdfdf;
            position: relative;
            text-align: center;
            margin: 0 25px 25px 0;
            float: left;
            transform: scale(1);
            transition: transform 0.3s linear;
            .flag{
              position: absolute;
              top: 0;
              right: 0;
              width: 66px;
              height: 66px;
              // background: url('../../assets/hot-flag.png');
              background-repeat: no-repeat;
              background-size: 100% 100%;
            }
            .game-list-hover{
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                // margin: -8px 0 0 -8px;
                // background: url('../../assets/game-list-code-hover.png') no-repeat;
                background-color: #31aae2;
                background-position: center;
                background-size: 180px 216px;
                opacity: 0;
                transition: opacity 0.4s linear;
                padding-top: 66px;
                box-sizing: border-box;
                .gameQrcode{
                  width: 120px;
                  height: 120px;
                  margin: 0 auto;
                  box-sizing: border-box;
                  padding: 10px 0;
                  background: #ffffff;
                  img{
                    margin: 0 auto;
                  }
                  p{
                    text-align: center;
                    font-size: 16px;
                    margin: 25px 0 0 0;
                    color: #ffffff;
                  }
                }
                .bn-wx-qrcode{
                  width: 144px;
                  height: 144px;
                  background: url('../../assets/bn-wx-qrcode.png') no-repeat;
                  background-size: 100% 100%;
                  margin: 0 auto;
                }
            }
            .list-icon{
                width: 112px;
                height: 112px;
                margin: 30px auto 0;
                display: block;
            }
            .list-tit{
                font-size: 24px;
                color: #848484;
                font-weight: 500;
                display: inline-block;
                height: 48px;
                line-height: 48px;
                margin-top: 7px;
                border-bottom: 2px solid #848484;
                min-width: 91px;
            }
            $mg-r: 8px;
            $font: 14px;
            p{
                margin: 6px 0 22px 0;
            }
            .list-type{
                color: #848484;
                font-size: $font;
                position: relative;
                margin-right: $mg-r;
            }
            .list-type:after{
                position: absolute;
                top: 4px;
                right: -$mg-r;
                content: "";
                width: 1px;
                height: 13px;
                background: #848484;
            }
            .download-num{
                color: #848484;
                font-size: $font;
            }
            .download-btn{
                display: block;
                height: 54px;
                width: 100%;
                background: #31aae2;
                color: #ffffff;
                text-align: center;
                font-size: 24px;
                line-height: 54px;
            }
        }
        // .game-list-item:hover{
        //     .game-list-hover{
        //         opacity: 1;
        //         z-index: 100;
        //         transition: opacity 0.26s linear;
        //     }
        //     transform: scale(1.06);
        //     transition: transform 0.3s linear;
        // }
        .game-list-item-hover{
          .game-list-hover{
                opacity: 1;
                z-index: 100;
                transition: opacity 0.26s linear;
            }
            transform: scale(1.06);
            transition: transform 0.3s linear;
        }
        .game-list-item:nth-child(4n){
            margin-right: 0;
        }
    }

}
.el-pagination{
  text-align: center;
}
.el-pagination.is-background .btn-next, .el-pagination.is-background .btn-prev, .el-pagination.is-background .el-pager li{
  background-color: #dedee1;
}
.el-pagination.is-background .el-pager li:not(.disabled).active{
  background-color: #31aae2;
}
</style>
