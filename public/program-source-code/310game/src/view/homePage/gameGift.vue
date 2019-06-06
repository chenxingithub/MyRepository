<template>
    <div class="game-gift">
        <div class="gift-title">
            <span>游戏礼包</span>
        </div>
        <div class="gift-con">
            <ul>
                <li>
                    <div class="gift-con-item">
                        <div class="con-item-icon">
                            <!-- <img src="../../assets/gift-item-icon.jpg" alt="gift"> -->
                        </div>
                        <div class="item-box">
                            <p class="item-tit">仙魔决官网礼包</p>
                            <p class="item-dec">还剩<span class="surplus">562</span>个</p>
                        </div>
                        <div class="con-item-btn" @click="handleGetGift('')">领取</div>
                    </div>
                </li>
                <li>
                    <div class="gift-con-item">
                        <div class="con-item-icon">
                            <!-- <img src="../../assets/gift-item-icon.jpg" alt="gift"> -->
                        </div>
                        <div class="item-box">
                            <p class="item-tit">仙门官网礼包</p>
                            <p class="item-dec">还剩<span class="surplus">866</span>个</p>
                        </div>
                        <div class="con-item-btn" @click="handleGetGift('')">领取</div>
                    </div>
                </li>
                <li>
                    <div class="gift-con-item">
                        <div class="con-item-icon">
                            <!-- <img src="../../assets/gift-item-icon.jpg" alt="gift"> -->
                        </div>
                        <div class="item-box">
                            <p class="item-tit">冰鸟游戏微信关注礼包</p>
                            <p class="item-dec">还剩<span class="surplus">668</span>个</p>
                        </div>
                        <div class="con-item-btn" @click="handleGetGift('/article?id=428')">领取</div>
                    </div>
                </li>
                <li>
                    <div class="gift-con-item">
                        <div class="con-item-icon">
                            <!-- <img src="../../assets/gift-item-icon.jpg" alt="gift"> -->
                        </div>
                        <div class="item-box">
                            <p class="item-tit">冰鸟游戏踏青礼包</p>
                            <p class="item-dec">还剩<span class="surplus">588</span>个</p>
                        </div>
                        <div class="con-item-btn" @click="handleGetGift('/article?id=428')">领取</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    name: 'gameGift',
    methods: {
      handleGetGift(url) {
        if(url) {
          this.$router.push(url);
          return false;
        }
        alert('敬请期待！');
      }
    }
}
</script>
<style lang="scss">
.game-gift{
    $gift-h: 374px;
    $title-h: 57px;//高度

    width: 100%;
    height: $gift-h;
    box-sizing: border-box;
    // background: rgba(75, 240, 226, 0.45);
    .gift-title{
        $pdtop: 24px;//padding-top

        height: $title-h - $pdtop;
        width: 100%;
        font-size: 18px;
        padding-top: $pdtop;
        border-bottom: 1px solid #ebebeb;
        position: relative;
        span{
            margin-left: 38px;
        }
        span:before{
            height: 20px;
            width: 5px;
            content: " ";
            background: #31aae2;
            position: absolute;
            top: $pdtop;
            left: 20px;
        }
    }

    .gift-con{

        ul{
            padding-top: 17px;
        }
        li{
            list-style: none;
            height: 52px;
            padding: 5px 30px 5px 27px;
        }
        li:nth-child(1){
          .gift-con-item {
            .con-item-icon{
                background-image: url('../../assets/gift-item-icon.jpg');
              }
          }
        }
        li:nth-child(2){
          .gift-con-item {
            .con-item-icon{
                background-image: url('../../assets/game-gift-1.png');
              }
          }
        }
        li:nth-child(3){
          .gift-con-item {
            .con-item-icon{
                background-image: url('../../assets/game-gift-2.png');
              }
          }
        }
        li:nth-child(4){
          .gift-con-item {
            .con-item-icon{
                background-image: url('../../assets/game-gift-3.png');
              }
          }
        }
        .gift-con-item{
            >div{
                float: left;
            }
            .con-item-icon{
                width: 52px;
                height: 52px;
                border-radius: 50%;
                background: url('../../assets/gift-item-icon.jpg');
                background-repeat: no-repeat;
                background-size: 100% 100%;
                margin-right: 6px;
            }
            .item-box{
                margin: 3px 0;
                >p{
                    margin-top: 5px;
                }
                .item-tit{
                    width: 150px;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                    font-size: 14px;
                    color: #000000;
                    transition: color 0.2s linear;
                }
                .item-dec{
                    font-size: 12px;
                    color: #8d8d8d;
                }
                .surplus{
                    color: #f83e5d;
                }
            }
            .con-item-btn{
                width: 50px;
                height: 24px;
                line-height: 24px;
                border-radius: 5px;
                background: #ffffff;
                border: 1px solid #31aae2;
                color: #31aae2;
                font-size: 14px;
                text-align: center;
                float: right;
                margin: 12px 0;
                cursor: pointer;
                transition: all 0.3s linear;
            }
        }

        .gift-con-item:hover{
            .item-tit{
                color: #31aae2;
                transition: color 0.2s linear;
            }
            .con-item-btn{
                background: #31aae2;
                color: #ffffff;
                transition: all 0.3s linear;
            }
        }
    }
}
</style>
