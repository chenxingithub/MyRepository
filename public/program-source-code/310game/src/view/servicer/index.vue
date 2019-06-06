<template>
    <div id="servicer">
      <head-nav></head-nav>
      <div class="head"></div>
      <div class="ser-content">
        <div class="ser-nav clearfix">
          <div class="left">
            <div class="tiem">
              <p>服务时间</p>
              <p>09&nbsp;:&nbsp;00 - 23&nbsp;:&nbsp;00</p>
            </div>
          </div>
          <div class="right">
            <div class="item"></div>
            <div class="item">
              <a target="_blank" href="http://q.url.cn/cdHhAo?_type=wpa&qidian=true"></a>
            </div>
            <div class="item">
              <a target="_blank" href="https://www.310game.com/newcms2/customer-service/#/RetrievePassword"></a>
            </div>
            <div></div>
          </div>
        </div>
        <div class="qust-box">
          <p class="tit"><b>常见问题</b></p>
          <div class="qust">
            <div class="qust-item">
              <p>1、如何找回冰鸟账号？</p>
              <div class="solution">
                <p>（1）如果您是一键注册账号，可翻看手机相册查看是否保存；</p>
                <p>（2）联系我们QQ客服公众号：800180310，提供相关角色信息为您查询</p>
                <p>（3）点击上方“申诉中心”选择“找回账号”提交申诉找回</p>
              </div>
              <div class="switch" @click="handleClick">查看详情&nbsp;∨</div>
            </div>
            <div class="qust-item">
              <p>2、怎么找回冰鸟游戏账号密码？</p>
              <div class="solution">
                <p>（1）如您是一键注册账号，可翻看手机相册查看保存的账号密码；</p>
                <p>（2）点击上方“申诉中心”选择“找回密码”填完完整申诉资料，提交申诉；我们会尽快审核您的资料帮您找回。</p>
              </div>
              <div class="switch" @click="handleClick">查看详情&nbsp;∨</div>
            </div>
            <div class="qust-item">
              <p>3、手机怎么截图</p>
              <div class="solution">
                <p>方法一：HOME (首页键) + 电源键</p>
                <p>此方式适用有实体按键的装置 (Note、S2、S3)，跟iOS的截图方式一样，按下HOME+电源键就可截图。</p>
                <p>方法二：Volume Dowm (音量 - 下键) + 电源键</p>
                <p>无实体按键装置基本上会是用这截图方式，不过依照装置会有"先按音量再按电源" 或 "同时按下"的差别。</p>
                <p>方法三：下拉手机通知栏选择截图快捷功能；手机型号不同，会有少许差别。</p>
              </div>
              <div class="switch" @click="handleClick">查看详情&nbsp;∨</div>
            </div>
            <div class="qust-item">
              <p>4、游戏闪退如何解决？</p>
              <div class="solution">
                <p>您好，出现闪退一般是手机CPU已经不堪重负，建议尝试以下改善方法：</p>
                <p>（1）清楚游戏缓存，应用程序-对应游戏-清除缓存数据；</p>
                <p>（2）关闭后台其他运营的程序；</p>
                <p>（3）更换网络，切换为4G/wifi后再次登录游戏；</p>
                <p>（4）卸载游戏客户端，重新到冰鸟游戏官网下载或联系冰鸟游戏客服、冰鸟手游微信公众号下载APP。</p>
              </div>
              <div class="switch" @click="handleClick">查看详情&nbsp;∨</div>
            </div>
            <div class="qust-item">
              <p>5、游戏充值失败如何处理？</p>
              <div class="solution">
                <p>您好，遇到这种情况请不要慌张，一般是因为充值渠道网络异常导致延迟到账问题，请您稍等5分钟后刷新重新进入游戏留意；</p>
                <p>如依然没收到，请按照以下格式整理好资料联系我们冰鸟游戏客服为您处理：</p>
                <p>冰鸟账号：</p>
                <p>区服：</p>
                <p>角色名：</p>
                <p>充值时间：</p>
                <p>充值金额：</p>
                <p>截图：手机成功扣费的截图。</p>
              </div>
              <div class="switch" @click="handleClick">查看详情&nbsp;∨</div>
            </div>
          </div>
        </div>
      </div>
      <foot></foot>
    </div>
</template>
<script>
import headNav from '../../components/head/index.vue'
import foot from '../../components/foot/index'

export default {
  name: 'servicer',
  components: {
    headNav,
    foot
  },
  methods: {
    handleClick(e) {
      let target = e.target;
      let node = target.previousElementSibling;
      let classLists = node.getAttribute('class');

      if(classLists.search(/solution-act/) > 0) {
         classLists = classLists.replace(/solution-act/, '');
         node.style.maxHeight = '0px';
         target.innerText = '查看详情 ∨';
      } else {
        classLists += ' ' + 'solution-act';
        node.style.maxHeight = '400px';
        target.innerText = '收起 ∧';
      }

      node.setAttribute('class', classLists.trim());
    }
  },
}
</script>
<style lang="scss">
#servicer{
  .head{
    width: 100%;
    min-width: 1200px;
    height: 376px;
    background: url('../../assets/service-head.png') no-repeat;
    background-size: cover;
    background-position: center top;
  }
  .ser-content{
    width: 1200px;
    margin: 0 auto;
  }
  .ser-nav{
    padding: 70px 45px 0;
    .left{
      float: left;
      width: 360px;
      height: 336px;
      background: url('../../assets/service-contact.png') no-repeat;
      background-size: 100% 100%;
      position: relative;
    }
    .tiem{
      position: absolute;
      bottom: 40px;
      left: 0;
      p{
        font-size: 24px;
        color: #ffffff;
        text-align: center;
        width: 360px;
      }
      p:nth-child(2){
        margin-top: 10px;
        text-decoration: underline;
      }
    }
    .right{
      float: right;
      height: 367px;
      width: 732px;
      >div{
        width: 360px;
        height: 160px;
        float: left;
        border-radius: 8px;
        margin: 0 0 16px;
        cursor: pointer;
        background-repeat: no-repeat;
        background-color: #ffffff;
        background-position: center;
        transition: all 0.3s linear;
      }
      >div:nth-child(1){
        background-size: 221px auto;
        background-image: url('../../assets/service-tel.png');
      }
      >div:nth-child(2){
        >a{
          display: block;
          width: 100%;
          height: 100%;
        }
        background-size: 172px auto;
        background-image: url('../../assets/service-qq.png');
      }
      >div:nth-child(3){
        >a{
          display: block;
          width: 100%;
          height: 100%;
        }
        background-size: 178px auto;
        background-image: url('../../assets/state-center.png');
      }
      >div:nth-child(4){
        background-size: 146px auto;
        background-image: url('../../assets/service-wx-icon.png');
      }
      >div:nth-child(odd){
        margin-right: 12px;
      }
      .item:hover{
        transform: translateY(-8px);
        box-shadow: -5px 6px 15px #d4d1d1;
        transition: all 0.3s linear;
      }
    }
  }
  .qust-box{
    margin: 76px auto 118px;
    .tit{
      margin-left: 34px;
      font-size: 34px;
      position: relative;
    }
    .tit:before{
      position: absolute;
      top: 0;
      left: -32px;
      content: '';
      width: 10px;
      height: 41px;
      background: #31aae2;
    }
    .qust{
      width: 1200px;
      min-height: 300px;
      box-sizing: border-box;
      padding: 0 30px 0;
    }
    .qust-item{
      border-bottom: 1px solid #e1e4e8;
      padding: 38px 0 50px;
      position: relative;
      >p{
        font-size: 20px;
        color: #545455;
      }
      .solution{
        max-height: 0px;
        margin-top: 20px;
        font-size: 18px;
        color: #7d7d80;
        overflow: hidden;
        transition: all 1s linear;
        >p{
          text-indent: 1em;
          line-height: 40px;
        }
      }
      .solution-act{
        max-height: 500px;
        height: auto;
        transition: all 1.2s linear;
      }
      .switch{
        width: 125px;
        height: 40px;
        text-align: center;
        line-height: 40px;
        position: absolute;
        bottom: 20px;
        right: 0;
        font-size: 20px;
        color: #545455;
        cursor: pointer;
        transition: all 0.3s linear;
      }
      .switch:hover{
        color: #31aae2;
        transition: all 0.3s linear;
      }
    }
    .qust-item:after{
      position: absolute;
      bottom: -2px;
      right: 22px;
      content: '';
      background: #31aae2;
      width: 77px;
      height: 3px;
    }
  }
}
</style>
