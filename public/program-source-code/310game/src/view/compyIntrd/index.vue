<template>
  <div id="compy-intrd" class="wrap swiper-container" ref="compyIntrd">
    <head-nav :class="{'head-nav-act': headStatus == 'show'}"></head-nav>
    <div class="intrd-nav">
      <div class="nav-flag" :style="{'top': navFlagPos + 'px'}"></div>
      <div @click="handSliceTo(0)" class="nav"><b>关于我们</b><br/><span>ABOUT US</span></div>
      <div @click="handSliceTo(1)" class="nav"><b>联系我们</b><br/><span>CONTACT US</span></div>
      <div @click="handSliceTo(2)" class="nav"><b>免责声明</b><br/><span>DISCLAIMER</span></div>
    </div>
    <div class="swiper-wrapper">
      <div class="swiper-slide page_1 page_box">
        <div class="head">
          <div class="head-tit">
            <p>冰鸟游戏是一家专注于手机游戏联合运营和推广渠道拓展为主营的企业。自成立以来</p>
            <p>冰鸟游戏始终坚持以“传播精品游戏，分享快乐！”为理念，以发行精品手机游戏为宗旨，</p>
            <p>致力于打造国内一流手游平台，现已推出多款广受玩家欢迎的手机游戏。</p>
          </div>
        </div>
        <div class="page_1-con">
          <div class="text">
            <p class="tit">关于我们</p>
            <p class="en-tit">ABOUT US</p>
            <p>冰鸟游戏作为国内极具实力的手游联运平台，汇集国内优秀精品手游，以专业的产品运营服务提供给玩家良好的游戏体验，为玩家打造一个绿色、健康的手机游戏平台。</p>
            <p>冰鸟汇聚游戏圈从业超过6年各种资深大咖，专注手机游戏联运及广告投放业务，在互联网投资冷却的大环境之下，凭借团队超强的视野格局、深厚背景与超强专业能力，仍融到千万资金为业务保驾护航，半年内在产品侧储备8款以上现金牛手游，运营侧强调用户精细化运作，打造属于用户高粘性手游平台。目前公司月流水已超过9千万，正处于高速发展中。</p>
            <p>如今，冰鸟游戏以开阔的心胸，期待着各路精英加入我们，与我们再创事业新高峰！今后，冰鸟游戏将继续以运营精品手游为业务核心，不断提高手游运营和服务质量，丰富渠道资源，全力打造国内领先的手游运营平台。</p>
            <p>手游平台：http://www.310game.com<br/>
            公司网址：http://www.ibingniao.com</p>
            <p>
              <b>我们的核心价值观</b><br/>
              激情、高效、务实、共赢
            </p>
            <p>我们生来就不是豪门，没有时间随波逐流，抑郁仿徨；</p>
            <p>我们生来就不是天才，没有时间牢骚满腹，虚度光阴；</p>
            <p>不按部就班，不浑浑噩噩，不颓废抱怨，不碌碌无为；</p>
            <p>我们始终怀揣梦想，坚持不懈，不畏前路，不惧将来。</p>
          </div>
          <div class="mouse-icon-1 mouse-icon"></div>
        </div>
      </div>
      <div class="swiper-slide page_2 page_box">
        <div class="page-2-head">
          <div class="page_2-con">
            <div class="text">
              <p class="tit">联系我们</p>
              <p class="en-tit">CONTACT US</p>
              <div class="contact-us clearfix">
                <div class="l">
                  <p><span></span><b>广州冰鸟网络科技有限公司</b></p>
                  <p>冰鸟游戏作为国内极具实力的手游联运平台，汇集国内优秀精品手游，以专业的产品运营服务提供给玩家良好的游戏体验，为玩家打造一个绿色、健康的手机游戏平台。</p>
                  <div class="item">
                    <img src="../../assets/bn-address.png" alt="address">
                    <p>公司地址</p>
                    <p>广州市天河区天河路490号壬丰大厦</p>
                  </div>
                  <div class="item">
                    <img src="../../assets/bn-tel.png" alt="tel">
                    <p>联系电话：020-38915471</p>
                    <p>游戏客服：800180310</p>
                  </div>
                  <div class="item">
                    <img src="../../assets/bn-time.png" alt="time">
                    <p>值班时间</p>
                    <p>9：00 -- 23：00</p>
                  </div>
                  <div class="item4">
                    <img src="../../assets/bn-email.png" alt="email">
                    <p>公司传真：(+86)020-38915471</p>
                    <p>电子邮件：ibn@ibingniao.com</p>
                  </div>
                </div>
                <div class="r">
                  <div class="bn-map">
                    <div class="bn-map-flag"></div>
                  </div>
                  <div class="route-intrd">
                    <p>乘车路线：</p>
                    <p>1、公交路线：B1 B2 B2A B3 B3C B3A B3B B4B B4A B4 B5 B5快 B6 B9</p>
                    <p>B13 B16 B20 B21 B25 B27 138路 185路 197路 218路等</p>
                    <p>50多条公交路线，岗顶站下车步行200米即到。</p>
                    <p>2、地铁三号线 至 “岗顶” 站C出口，出地铁后向左边往前走约400米即到。</p>
                    <p>3、地铁三号线 至 “石牌桥”站B出口，出地铁后向右边往前走约300米即到。</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mouse-icon-2 mouse-icon"></div>
        </div>
      </div>
      <div class="swiper-slide page_3 page_box">
        <div class="page_3-con">
          <div class="text">
            <p class="tit">免责声明</p>
            <p class="en-tit">DISCLAIMER</p>
            <div class="disclaimer">
              <p>1、本平台用户应依法对其提供的内容承担全部责任，本平台不承担任何责任。</p>
              <p>2、本平台用户上传的内容仅代表作者观点，不代表本平台观点。</p>
              <p>3、本平台内容，包括以链接形式推荐的其他网站内容，在法律允许的最大范围内，本平台不担保这些内容、产品、服务或其他材料的真实性、完整性、安全性和合法性。用户应谨慎判断并自担风险，避免任何因使用或信赖此类内容、产品、服务或其他材料而造成的任何直接或间接损失。</p>
              <p>4、本平台内容仅供参考，不做为任何行为的指导和建议。</p>
              <p>5、本平台内容受到著作权法、商标法、专利法、信息网络传播权保护条例等相关法律法规的保护和约束，除非获得权利人的授权，用户不得转载、修改、传播、制作衍生作品或者商业使用。用户或其他媒体、企业、网站、其他组织或个人对本平台内容如需使用，必须先与作者、相关权利人或本平台联系。</p>
              <p>6、本平台部分频道提供上传空间，供用户自行上传作品，分享游戏、技巧、心得或资讯等，用户上传作品应遵守《上传须知》及平台其他条款，如上传内容侵犯了第三方的知识产权或其他权利，由上传该内容的用户承担责任，本平台不承担任何责任。</p>
              <p>7、用户应妥善保管账户及密码信息，如用户将密码告知他人或与他人共享同一ID，从而导致任何资料泄露、积分丢失等任何损失，由用户自行承担。</p>
              <p>8、本平台可能因合作方或相关电信部门的互联网软硬件设备故障或失灵、或人为操作疏忽而全部或部分中断、延迟、遗漏、误导或造成资料传输或储存上的错误、或遭第三人侵入系统篡改或伪造变造资料等，对此用户同意理解和接受。</p>
              <p>9、本平台可能因黑客攻击、计算机病毒侵入或发作、政府管制而造成的暂时性关闭，或因前述原因以及与本网站链接的其它网站原因导致个人资料泄露、丢失、被盗用或被篡改等，对此用户同意理解和接受。</p>
              <p>10、本平台有权在任何时候，修改或暂停、终止本平台全部或部分服务，对此用户可以理解和接受。</p>
            </div>
          </div>
        </div>
        <foot></foot>
      </div>
    </div>
  </div>
</template>
<script>
import $ from 'jquery'
import Swiper from '../../plugins/swiper/idangerous.swiper2.7.6.js'
import '../../plugins/swiper/idangerous.swiper2.7.6.css'
import headNav from '../../components/head/index.vue'
import foot from '../../components/foot/index'

export default {
  name: 'compyIntrd',
  components: {
    headNav,
    foot
  },
  data() {
    return {
      headStatus: 'hide',
      actIndex: '',
      navFlag: [0,70,140],
      index: 0
    }
  },
  computed: {
    navFlagPos() {
      return this.navFlag[this.index];
    }
  },
  methods: {
    wrap() {//整屏滚动适配
      let _this = this;
      let i, s, n = $(window),
        o = $("#compy-intrd"),
        t = this.swiper,
        a = $(".menu a"),
        r = $(".swiper-slide.page_box");

      if (i = n.height(), s = n.width(), n.height() > 850 ? (i = n.height(), o.css("height", i)) : (i = 1e3, o.css("height", n.height())), s = n.width() > 1200 ? n.width() : 1200, n.width() > 1500 ? a.show() : a.hide(), r.css("height", i),r.eq(0).css("height", 1572), r.eq(2).css("height", 1290), null == t)
            this.swiper = new Swiper("#compy-intrd", {
            mode: "vertical",
            speed: 800,
            mousewheelControl: true,
            simulateTouch: !1,
            resizeReInit: !0,
            observer: !0,
            mousewheelControlForceToAxis : true,
            observeParents: !0,
            slidesPerView: "auto",
            initialSlide: 0,
            onFirstInit: function(e) {
              let intrdId = _this.$route.query.intrdId;
              if(intrdId != 0) {
                _this.headStatus = 'show';
              } else {
                _this.headStatus = 'hide';
              }
              e.swipeTo(intrdId, 1000, false);
              _this.index = intrdId;
            },
            onSlideChangeStart: function(e) {//slide 滚动时触发
                _this.index = e.activeIndex;
                if(e.activeIndex != 0) {
                  _this.headStatus = 'show';
                } else {
                  _this.headStatus = 'hide';
                }
                if(_this.activeSlide != e.activeIndex) {
                  _this.activeSlide = e.activeIndex;
                } else {
                  return false;
                }
            },
        });
        else {
          this.swiper.reInit();
          var e = t.activeIndex;
          t.swipeTo(e)
        }
    },
    handSliceTo(index) {
      if(index != 0) {
        this.headStatus = 'show';
      } else {
        this.headStatus = 'hide';
      }
      this.index = index;
      this.swiper.swipeTo(index, 1000, false);
    }
  },
  mounted() {
    this.wrap();
    $(window).resize(() => { this.wrap() });
  },
  beforeRouteUpdate(to, from, next) {
    this.index = to.query.intrdId;
    this.swiper.swipeTo(to.query.intrdId, 1000, false);
    next();
  }
}
</script>
<style lang="scss">
#compy-intrd{
  min-width: 1200px;
  font-family: "Microsoft YaHei";
  position: relative;
  overflow: hidden;
  .head-nav{
    transition: all 0.4s linear;
  }
  .head-nav-act{
    top: -100px;
    transition: all 0.4s linear;
  }
  .intrd-nav{
    position: fixed;
    z-index: 2;
    top: 250px;
    right: 20px;
    width: 130px;
    height: 190px;
    padding-left: 13px;
    box-sizing: border-box;
    border-left: 1px solid #acacac;
    .nav{
      height: 43px;
      margin-bottom: 25px;
      line-height: 24px;
      cursor: pointer;
    }
    b{
      color: #7a7a7a;
      font-size: 16px;
    }
    span{
      font-size: 14px;
      color: #9e9fa0;
    }
    .nav-flag{
      position: absolute;
      top: 0;
      left: -3px;
      width: 7px;
      height: 41px;
      background: #31aae2;
      transition: all 0.6s linear;
    }
  }
  .swiper-slide {
    position: relative;
    width: 100%;
    min-width: 1200px;
    background: transparent;
    .text{
      width: 1200px;
      margin: 0 auto;
      padding: 0 40px;
      box-sizing: border-box;
      .tit{
        font-size: 40px;
        text-align: center;
        font-weight: 600;
        position: relative;
      }
      .tit:after{
        position: absolute;
        left: 50%;
        content: '';
        margin-left: -67px;
        width: 134px;
        height: 2px;
        background: #b6b7ba;
      }
      .en-tit{
        text-align: center;
        font-size: 18px;
        margin: 33px auto;
        color: #7b7b7b;
      }
    }
  }
  //mouse-icon-2 mouse
  .mouse-icon{
    width: 26px;
    height: 80px;
    position: absolute;
    bottom: 10px;
    left: 50%;
    margin-left: -13px;
    // background: #31aae2;
    background-repeat: no-repeat;
    background-size: 100% 100%;
  }
  .page_1{
    width: 100%;
    .page_1-con{
      width: 100%;
      height: 1046px;
      background: url('../../assets/about-us-bg.jpg') no-repeat;
      background-size: 1920px 1046px;
      background-position: center top;
      p{
        margin: 36px 0;
      }
    }
    .tit:after{
      top: 55px;
    }
    p{
      line-height: 30px;
      font-size: 16px;
    }
    .mouse-icon{
      animation: moveDown 2.5s infinite;
      background-image: url('../../assets/mouse-icon-1.png');
    }
  }
  .page_2{
    width: 100%;
    .page_2-con{
      width: 100%;
      height: 859px;
      background: url('../../assets/contact-us-bg.jpg') no-repeat;
      background-size: 1920px 859px;
      background-position: center top;
      padding-top: 54px;
    }
    .tit{
      color: #ffffff;
    }
    .tit:after{
      top: 63px;
    }
    .en-tit{
      color: #676769;
    }
    .mouse-icon{
      animation: moveDown 2.5s infinite;
      background-image: url('../../assets/mouse-icon-2.png');
    }
    .contact-us{
      width: 1200px;
      font-size: 16px;
      color: #ffffff;
      .l{
        width: 570px;
        height: 650px;
        float: left;
        box-sizing: border-box;
        padding: 0 15px;
        >p{
          line-height: 30px;
        }
        >p:nth-child(1){
          position: relative;
          padding-left: 15px;
        }
        >p:nth-child(1):before {
          content: '';
          position: absolute;
          left: 0;
          top: 6px;
          width: 3px;
          height: 18px;
          background: #ffffff;
        }
        >p:nth-child(2){
          margin: 20px 0 50px;
          text-indent: 2em;
        }
        >div{
          margin: 30px 0;
          img{
            width: 30px;
            margin-left:15px ;
            margin-right:15px ;
            float: left;
            vertical-align: middle;
          }
          p{
            line-height: 30px;
          }
        }
      }
      .r{
        width: 600px;
        height: 650px;
        float: right;
        box-sizing: border-box;
        padding: 0 15px;
        .bn-map{
          position: relative;
          width: 571px;
          height: 307px;
          background: url('../../assets/bn-map.png') no-repeat;
          background-size: 100% 100%;
        }
        .bn-map-flag{
          position: absolute;
          top: 86px;
          left: 273px;
          width: 109px;
          height: 122px;
          background: url('../../assets/bn-map-flag.png') no-repeat;
          background-size: 100% 100%;
          transform: translateY(0);
          transition: transform 0.3s ease;
        }
        .bn-map:hover{
          .bn-map-flag{
            transform: translateY(-10px);
            transition: transform 0.3s ease;
          }
        }
      }
      .route-intrd{
        margin-top: 26px;
        line-height: 30px;
        >p:nth-child(3){
          text-indent: 6.6em;
        }
        >p:nth-child(4){
          text-indent: 6.6em;
        }
      }
      .item{
        img{
          margin-top: 14px;
        }
      }
      .item4{
        img{
          margin-top: 20px;
        }
      }
    }
  }
  .page_3{
    width: 100%;
    .page_3-con{
      position: relative;
      width: 100%;
      height: 1000px;
      background: url('../../assets/disclaimer-bg.jpg') no-repeat;
      background-size: 1920px 1051px;
      background-position: center top;
      padding-top: 50px;
    }
    .disclaimer{
      width: 1125px;
      margin: 0 auto;
      p{
        line-height: 30px;
        font-size: 16px;
        margin: 30px 0;
      }
    }
    .tit:after{
      top: 63px;
    }
    .mouse-icon{
      animation: moveDown 2.5s infinite;
      background-image: url('../../assets/mouse-icon-1.png');
    }
  }
  .page_4{
    width: 100%;
  }
  .head{
    position: relative;
    width: 100%;
    min-width: 1200px;
    height: 526px;
    background: url('../../assets/compy-intrd-head.png') no-repeat;
    background-size: cover;
    background-position: center top;
  }
  .head-tit{
    position: absolute;
    left: 50%;
    width: 800px;
    margin-left: -400px;
    line-height: 30px;
    font-size: 14px;
    color: #fefefe;
    text-align: center;
    bottom: 110px;
  }
}
@keyframes moveDown {
  0%, 100% {
      -webkit-transform: translate3d(0,0,0);
      transform: translate3d(0,0,0);
      opacity: 0;
  }
  50% {
      -webkit-transform: translate3d(0,-50%,0);
      transform: translate3d(0,-50%,0);
      opacity: 1;
  }
}
</style>
