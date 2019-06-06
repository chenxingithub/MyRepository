<template>
    <div id="foot">
        <div class="foot-copyright">
            <ul class="clearfix">
                <li class="parents-care"><a href="https://www.ibingniao.com/newcms2/jzjh/index.html" target="_blank">家长监护</a></li>
                <li><a href="https://www.310game.com/service?type=business" target="_blank">商务合作</a></li>
                <li><a href="https://www.310game.com/service?type=contant" target="_blank">联系我们</a></li>
                <li><a href="https://www.310game.com/service?type=about" target="_blank">关于我们</a></li>
                <li><a href="https://www.ibingniao.com/zhaopin" target="_blank">人才招聘</a></li>
                <li><a href="https://www.310game.com/service?type=custom" target="_blank">客服中心</a></li>
                <li><a href="https://www.310game.com/service?type=exonerationState" target="_blank">免责声明</a></li>
            </ul>
            <div>
                <div class="logo"></div>
                <div class="copyright-cont">
                    <p>软著权人：天津龙游网络科技有限公司 <span class="copyright-span1">粤ICP备16064025号-2</span> 客服电话 : 020 - 38915471</p>
                    <p>科技与数字[2011]065号 <span class="copyright-span2">丨</span> ISBN 978-7-7979-9002-3 <span class="copyright-span2">丨</span> 文网游备字〔2018〕Ｍ-RPG 0580 号</p>
                    <p>广州冰鸟网络科技有限公司  <span class="copyright-span3">粤ICP备16064025号-2</span> <span class="copyright-span3">丨 粤网文〔2016〕3892-917号</span> 丨 粤B2-20180692</p>
                    <p class="copyright-cont-tip">抵制不良游戏，拒绝盗版游戏。注意自我保护，谨防受骗上当。适度游戏益脑，沉迷游戏伤身。合理安排时间，享受健康生活。</p>
                </div>
                <div class="whjy">
                    <a href="http://sq.ccm.gov.cn/ccnt/sczr/service/business/emark/toDetail/7f69726c7ac74d8f9cad720d635d72ff" target="_blank" class="bn">
                        <img src="../../assets/images/whjy.png" alt="logo">
                    </a>
                    <a href="javascript:;" target="_blank" class="dzbq">
                        <img src="../../assets/images/youxidzbq.png" alt="youxidzbq">
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'foot'
}
</script>
<style lang="scss">
#foot{
    width: 100%;
    min-width: 1260px;
    background: #0a0a0a;
    height: 237px;
    // padding: 30px 0 0;
    .foot-copyright{
        width: 1200px;
        margin: 0 auto;
        height: 200px;
        position: relative;
        background: #0a0a0a;
        a{
            color: #c2c2c2;
            transition: all 0.3s linear;
        }
        a:hover{
            text-decoration: underline;
            color: #8a0e0e;
            transition: all 0.3s linear;
        }
        >ul{
          height: 80px;
           li{
              float: left;
              list-style: none;
              color: #81bcd8;
              font-size: 16px;
              text-align: right;
              width: 170px;
              line-height: 80px;
           }
        }
        .parents-care{
            width: 120px;
            height: 30px;
            // border-radius: 15px;
            background: #970e0f;
            line-height: 30px;
            margin: 28px 50px 0 0;
            text-align: center;
            a{
              color: #ffffff;
              transition: all 0.3s linear;
            }
            a:hover{
              text-decoration: none;
              color: #bdcb5e;
              transition: all 0.3s linear;
            }
        }
    }
    .logo{
        width: 232px;
        height: 67px;
        background: url('../../assets/images/bnlogo.png') no-repeat;
        background-size: 100% 100%;
        position: absolute;
        left: 0;
        top: 112px;
    }
    .copyright-cont{
        color: #939393;
        // margin-top: 33px;
        border-top: 1px solid #1e1e1e;
        padding:  20px 0 0 270px;
        font-size: 14px;
        p{
            line-height: 1.8em;
        }
        .copyright-cont-tip{
            margin: 15px 0;
            color: #303030;
        }
        .copyright-span1{
          margin: 0 104px;
        }
        .copyright-span2{
          margin: 0 44px;
        }
        .copyright-span3{
          margin: 0 32px;
        }
    }
    .whjy{
        position: absolute;
        top: 150px;
        right: 46px;
        a{
            margin-left: 25px;
        }
    }
}
</style>
