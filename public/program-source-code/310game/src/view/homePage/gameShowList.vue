<template>
    <div class="game-show-list">
        <div class="game-list-tit">
            <span class="list-tit">游戏推荐</span>
            <span class="list-next" @click="clickExchangeBtn">
                <div class="circular-arrow"></div> 换一换
            </span>
        </div>
        <div class="list-con-box clearfix"
            v-if="gameList">
            <div class="list-con-item animated"
                ref="gameListItem" v-for="(item, key) in gameList.data"
                :key="key" v-if="gameList.data">
                <div class="item-img-box" :style="{'background-image': item.bg_img ? 'url('+imgDomain + '/' + item.bg_img +')' : ''}">
                    <div class="item-download-panel">
                        <div class="download-code" v-show="item.enclosure_url">
                            <div :id="'qrcode'+key"></div>
                        </div>
                        <div class="download-wx-code" v-show="!item.enclosure_url"></div>
                        <div class="download-dec">
                            <p>{{ item.subtitled }}</p>
                            <p>
                              <img src="../../assets/download-icon.png" alt="download">
                              <span>立即下载</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="item-dec-box">
                    <a :href="handleGameOfficalUrl(item.jump_url)" target="_blank" class="entry-link">进入官网 <div class="arrow"></div></a>
                    <p class="item-tit">{{ item.title }}</p>
                    <p class="item-dec">{{ item.sketch }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import QRCode from 'qrcodejs2'
import { request_articleList } from '../../api/index.js'
import { mapState } from 'vuex'
import 'animate.css'

export default {
    name: 'gameShowList',
    data() {
        return {
            gameList: null,
            id: 68,//游戏列表
            page: 1,
            limit: 4
        }

    },
    computed: {
        ...mapState(['imgDomain'])
    },
    methods: {
        qrcode () { //生成二维码
            if(!this.gameList) {
                return false;
            }
            this.$nextTick (() => {// 节点渲染结束后添加生成二维码

                for(let [index, val] of Object.entries(this.gameList.data)) {
                    let qrcode = new QRCode('qrcode'+index, {
                        width: 102,  // 设置宽度
                        height: 102, // 设置高度
                        text: val.enclosure_url //附件链接 -> 下载链接
                    });
                }
                // for(let [index, val] of Object.entries(this.$refs.gameListItem)) {
                //     val.style.transform = 'scale(1)';
                //     log( val.style.transform);
                // }
            });
        },
        gameListReq(id, page, limit) {
            request_articleList(id, page, limit).then(resolve => {

                this.gameList = resolve.data[this.id].data[0];
            });
        },
        clickExchangeBtn() {
            let page = 0;

            this.gameList.data = null;
            if(this.page >= this.gameList.last_page) {
                page = 1;
                this.page = 1;
                this.gameListReq(this.id, page, this.limit);
                return false;
            }
            this.page += 1;
            // page = this.page + 1;
            this.gameListReq(this.id, this.page, this.limit);

            // for(let [index, val] of Object.entries(this.$refs.gameListItem)) {
            //     val.style.transform = 'scale(0)';
            //     log( val.style.transform);
            // }
        },
        handleGameOfficalUrl(url) {
          return url || "javascript:alert('敬请期待！');";
        }
    },
    created() {
        this.gameListReq(this.id, this.page, this.limit);//首次加载请求

    },
    watch: {
        'gameList': 'qrcode'
    }
}
</script>
<style lang="scss">
.game-show-list{
    width: 870px;
    width: 100%;
    height: 667px;
    // background: rgba(211, 131, 248, 0.35);
    padding: 0 17px;
    box-sizing: border-box;
    .game-list-tit{
        width: 100%;
        // height: 43px;
        padding-top: 23px;
        height: 20px;
        position: relative;
        .list-tit{
            margin-left: 18px;
            font-size: 18px;
        }
        .list-tit:before{
            height: 20px;
            width: 5px;
            content: " ";
            background: #31aae2;
            position: absolute;
            top: 23px;
            left: 0;
        }
        .list-next{
            float: right;
            font-size: 18px;
            cursor: pointer;
            color: #000000;
            // transition: color 0.3s linear;
            .circular-arrow{
              display: inline-block;
              width: 17px;
              height: 17px;
              margin: -2px 0;
              background: url('../../assets/circular-arrow.png');
              background-repeat: no-repeat;
              background-size: 100% 100%;
            }
        }
        .list-next:hover{
          color: #31aae2;
          // transition: color 0.18s linear;
          .circular-arrow{
            background-image: url('../../assets/circular-arrow-1.png');
          }
        }
    }
    .list-con-box{
        margin-top: 17px;
        overflow: hidden;
        .list-con-item{
            min-height: 271px;
            width: 405px;
            float: left;
            margin-bottom: 24px;
            // transform: scale(0);
            // transition: transform .4s linear 2s;
            // transition-delay: 2s;
            .item-img-box{
                width: 100%;
                height: 180px;
                // background-color: #31aae2;
                background: url('../../assets/game-icon.png');
                background-size: 100% 100%;
                position: relative;
                overflow: hidden;
                .item-download-panel{
                    width: 100%;
                    height: 100%;
                    position: absolute;
                    top: -100%;
                    left: 0;
                    background-color: rgba(50, 159, 210, 1);
                    opacity: 0;
                    filter: alpha(opacity=0);
                    transition: all 0.3s ease-in;
                    text-align: center;
                    >div{
                        // float: left;
                        display: inline-block;
                        color: #ffffff;
                        font-size: 20px;
                    }
                    .download-code{
                        margin: 34px 0;
                        width: 112px;
                        height: 112px;
                        background: #ffffff;
                        text-align: center;
                        img{
                            // height: 112px;
                            margin: 5px;
                        }
                    }
                    .download-wx-code{
                        margin: 34px 0;
                        width: 112px;
                        height: 112px;
                        background: url('../../assets/bn-wx-qrcode.png') no-repeat;
                        background-size: 100% 100%;
                        background-color: #ffffff;
                    }
                    .download-dec{
                        margin-left: 18px;
                        margin-top: 24px;
                        vertical-align: top;
                        text-align: left;
                        img{
                            width: 19px;
                            height: 19px;
                            margin-right: 5px;
                        }
                        p{
                            margin-top: 16px;
                        }
                    }
                }

            }
            .item-img-box:hover{
                .item-download-panel{
                    top: 0;
                    background-color: rgba(50, 159, 210, .85);
                    transition: all 0.3s ease-out;
                    opacity: 1;
                    filter: alpha(opacity=100);
                }
            }
            .item-dec-box{
                position: relative;
                margin-top: 16px;
                max-height: 75px;
                overflow: hidden;
                .entry-link{
                    float: right;
                    color: #8d8d8d;
                    margin-top: 4px;
                    cursor: pointer;
                    transition: all 0.2s linear;
                }
                .arrow{
                  display: inline-block;
                  width: 13px;
                  height: 10px;
                  background: url('../../assets/l-arrow-un.png');
                  background-repeat: no-repeat;
                  background-size: 100% 100%;
                  background-position: center;
                }
                .entry-link:hover{
                    color: #31aae2;
                    transition: all 0.2s linear;
                    .arrow{
                      background-image: url('../../assets/l-arrow.png');
                    }
                }
                .item-tit{
                    font-size: 24px
                }
                .item-dec{
                    color: #8d8d8d;
                    font-size: 14px;
                    margin-top: 16px;
                    overflow: hidden;
                    text-align: justify;
                }
            }
        }
        // .list-con-item:nth-of-type(odd){

        // }
        .list-con-item:nth-of-type(even){
            float: right;
        }
    }
}
</style>
