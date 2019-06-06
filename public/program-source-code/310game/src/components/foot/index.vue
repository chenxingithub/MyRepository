<template>
    <div id="foot">
        <div class="foot-copyright">
            <ul class="clearfix">
                <li class="parents-care"><a href="https://www.ibingniao.com/newcms2/jzjh/index.html" target="_blank">家长监护</a></li>
                <li><router-link :to="{path: '/compyIntrd', query: {intrdId: 1}}">商务合作</router-link></li>
                <li><router-link :to="{path: '/compyIntrd', query: {intrdId: 1}}">联系我们</router-link></li>
                <li><router-link :to="{path: '/compyIntrd', query: {intrdId: 0}}">关于我们</router-link></li>
                <li><a href="https://www.ibingniao.com/zhaopin" target="_blank">人才招聘</a></li>
                <li><router-link :to="{path: '/servicer'}">客服中心</router-link></li>
                <li><router-link :to="{path: '/compyIntrd', query: {intrdId: 2}}">免责声明</router-link></li>
            </ul>
            <div>
                <div class="logo"></div>
                <div class="copyright-cont">
                    <p>广州冰鸟网络科技有限公司 粤ICP备16064025号-1 客服电话：020-38915471</p>
                    <p>增值电信业务经营许可证：粤B2-20180692&nbsp;<router-link :to="{path: '/servicer'}">纠纷处理</router-link></p>
                    <p>
                      粤网文〔2016〕3892-917号COPYRIGHT @2016 ALL RIGHTS RESERVED
                      <a href="http://sq.ccm.gov.cn/ccnt/sczr/service/business/emark/toDetail/7f69726c7ac74d8f9cad720d635d72ff" target="_blank" class="bn whjy">
                          <img src="../../assets/whjy.png" alt="logo">
                      </a>
                    </p>
                    <!-- <p class="copyright-cont-tip">抵制不良游戏，拒绝盗版游戏。注意自我保护，谨防受骗上当。适度游戏益脑，沉迷游戏伤身。合理安排时间，享受健康生活。</p> -->
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
    background: #151515;
    height: 240px;
    padding: 50px 0 11px;
    box-sizing: border-box;
    .foot-copyright{
        width: 1200px;
        margin: 0 auto;
        height: 176px;
        position: relative;
        background: #151515;
        a{
            color: #ffffff;
            transition: all 0.3s linear;
        }
        a:hover{
            text-decoration: underline;
            color: #33a0d3;
            transition: all 0.3s linear;
        }
        >ul{
           li{
               float: left;
               list-style: none;
               color: #33a0d3;
               font-size: 18px;
               text-align: right;
               width: 170px;
              line-height: 30px;
           }
        }
        .parents-care{
            width: 120px;
            height: 30px;
            border-radius: 5px;
            background: #33a0d3;
            line-height: 30px;
            margin: 0 50px 0 0;
            text-align: center;
            a{
                color: #ffffff;
            }
            a:hover{
                text-decoration: none;
            }
        }
    }
    .logo{
        width: 232px;
        height: 67px;
        background: url('../../assets/bnlogo-icon.png') no-repeat;
        background-size: 100% 100%;
        position: absolute;
        left: 0;
        top: 80px;
    }
    .copyright-cont{
        color: #939393;
        margin-top: 20px;
        border-top: 1px solid #1e1e1e;
        padding:  30px 0 0 270px;
        font-size: 14px;
        p{
            line-height: 1.5em;
        }
        .copyright-cont-tip{
            margin-top: 20px;
        }
        a{
          font-size: 14px;
          color: #939393;
        }
    }
    .whjy{
        // position: absolute;
        // top: 60px;
        // right: 0;
        img{
          vertical-align: middle;
        }
    }
}
</style>
