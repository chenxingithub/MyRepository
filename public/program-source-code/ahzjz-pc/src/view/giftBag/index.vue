<template>
  <div id="giftBag">
    <div class="giftBag-content">
      <div class="gift-head"></div>
      <div class="gift-nav clearfix">
        <div class="gift-nav-txt">
          <span><img src="../../assets/images/new-giftbag-bg.png" alt=""></span>
        </div>
        <router-link to="/" tag="div" class="gift-return"></router-link>
      </div>
      <div class="gift-list-content" v-if="giftCodeList.length">
        <div class="gift-list-item" v-for="(item, index) in giftCodeList" :key="index">
          <p class="gift-list-tit">
            <span>{{ item.cdk_type_title }}</span>
          </p>
          <p class="gift-list-dec">
            {{ item.cdk_type_content }}
          </p>
          <p class="gift-percentage-bar">
            <span :style="{width: Math.round(item.surplus/item.cdk_total_num*100)+'%'}"></span>
          </p>
          <p class="gift-list-percentage">
            剩余{{ Math.round(item.surplus/item.cdk_total_num*100) }}%
          </p>
          <button class="get-code-btn"
            @click="getCode(item.id, item.cdk_type_title, item.cdk_type_content, item.expired_at,item.isGet)">领取</button>
        </div>
      </div>
    </div>

    <div class="modal-cover" v-show="getCodeModal=='show'">
      <div class="modal-content">
        <div class="modal-bg border-box">
          <div class="close" @click="getCodeModalClose"></div>
          <p>以下是您的&nbsp;&nbsp;<span>首发大菠萝礼包</span>&nbsp;&nbsp;兑换码，请尽快登录游戏进行兑换</p>
          <div class="code-box">
            <span>{{ code }}</span>
            <span :data-clipboard-text="code" class="copy-btn">复制</span>
          </div>
        </div>
      </div>
    </div>
    <tip-message></tip-message>
  </div>
</template>
<script>
import foot from '../../components/foot/index'
import ClipboardJS from 'clipboard';
import { gift_code_list, get_gift_code } from '../../api/index.js';
import { GetQueryString, getLocalStore, storeToLocal } from '../../utils/utils.js';
import tipMessage from '../../components/tipMessage'
import { mapActions } from 'vuex'

export default {
  name: 'giftBag',
  components: {
    tipMessage,
    foot
  },
  data() {
    return {
      getCodeModal: 'hide',
      page: 1,
      limit: 10,
      giftCodeList: [],
      code: '',
      getGiftCodeLists: [],
      devFlag: this.$route.query.dev || '',
      clipboard: null //ClipboardJS 复制对象
    };
  },
  computed: {
    handle_giftCodeList() {
      let lists = [];
      for(let item of this.giftCodeList) {

        lists.push(item);
        lists[lists.length-1].isGet = this.isGetCode(item.id);
      }
      return lists;
    }

  },
  methods: {
    ...mapActions([
      'tipModalAct'
    ]),
    getCode(id) {
      let _this = this;
      console.log(id[0]);
      let giftCodeList = getLocalStore('codeLists') || [];

      for(let i = 0 ,len=giftCodeList.length; i < len ; i++) {//本地存在领取记录直接返回礼包码并显示弹窗
          if(giftCodeList[i].id == id) {
            // console.log(giftCodeList[i].id);
            // console.log(id);
            this.code = giftCodeList[i].code;
            this.getCodeModal = 'show';
            this.clickCopy();//初始化复制对象
            return false;
          }
      }
      get_gift_code(id)
          .then(response => {
            let data = response.data.data

            if(data) {
                  _this.code = data.code;
            } else {
                alert(response.data.message);
                return false;
            }
            _this.getCodeModal = 'show';
            this.clickCopy();//初始化复制对象
            let codeList = {
              id: id,
              title: title,
              describe: describe,
              code: data.code,
              overTime: overTime,
              expiredStatus: 1//默认未过期
            };

            giftCodeList.push(codeList);
            storeToLocal('codeLists',giftCodeList);

            // console.log(id);
            for(let index of _this.giftCodeList.keys()) {
              if(_this.giftCodeList[index].id == id) {

                // console.log(_this.giftCodeList[index]);
                if(_this.giftCodeList[index].surplus >=1) {
                    _this.giftCodeList[index].surplus -= 1;
                }

                _this.giftCodeList[index].isGet = true;
                return false;
              }
              // console.log(index);
            }

        });
    },
    getCodeModalClose() {
        this.getCodeModal = 'hide';
    },
    isGetCode (id) {//遍历存储的数据看是否领取过礼包码
      let getGiftCodeLists = this.getGiftCodeLists || [];

      return getGiftCodeLists.some(function(item,index) {
          return item.id == id;
      });
    },
    clickCopy() {

      // if(this.clipboard) { return false; }
      let _this = this;
      this.clipboard = new ClipboardJS('.copy-btn');

      this.clipboard.on('success', function(e) {
          _this.tipModalAct({msg: '复制成功！', type: 'success'});
          e.clearSelection();
      });

      this.clipboard.on('error', function(e) {
        _this.tipModalAct({msg: '复制失败！', type: 'success'});
      });
    }
  },
  created: function() {
    this.getGiftCodeLists = getLocalStore('codeLists');
  },
  mounted: function() {
    let  _this = this;
    //获取礼包列表
    gift_code_list(_this.page, _this.limit)
      .then(function(response) {
        _this.giftCodeList = [..._this.giftCodeList , ...response.data.data]
      });
  }
}
</script>
<style lang="scss">
#giftBag{
  width: 100%;
  height: 100%;
  position: relative;
  @include bg('../../assets/images/gift-bg.png', 1920px, 1080px, center, top);
  background-attachment: fixed;
  .giftBag-content{
    width: 1200px;
    min-height: 985px;
    margin: 0 auto;
    padding-top: 95px;
  }
  .gift-head{
    width: 100%;
    height: 65px;
    @include bg('../../assets/images/gift-tit.png', 1175px, 65px, center, top);
  }
  .gift-nav{
    box-shadow:inset 0 -2px 0 0 #7b7b7b;
    width: 1166px;
    margin: 0 auto;
    .gift-nav-txt{
      float: left;
      margin-top: 46px;
      span{
        font-size: 28px;
        font-weight: 500;
        // background: linear-gradient(to bottom, #ffffff, #757575);
        // -webkit-background-clip: text;
        color: transparent;
      }
    }
    .gift-return{
      float: right;
      width: 241px;
      height: 92px;
      cursor: pointer;
      @include bg('../../assets/images/toHome-btn.png', 100%, 100%, 15px, top);
    }
  }
  .gift-list-content{
    width: 1166px;
    height: 640px;
    margin: 0 auto;
    overflow: hidden;
    .gift-list-item{
      width: 598px;
      height: 195px;
      float: left;
      box-sizing: border-box;
      position: relative;
      margin-top: 17px;
      padding: 38px 27px 0 32px;
      box-shadow: inset 0 0 0 1px #7b7b7b;
      user-select: none;
      .gift-list-tit{
        font-size: 24px;
        color: #ffffff;
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        height: 40px;
        overflow-x: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      .gift-list-tit:after{
        width: 172px;
        height: 2px;
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        z-index: 5;
        background: #3a383a;
      }
      .gift-list-dec{
        font-size: 22px;
        color: #c5c5c5;
        margin: 14px 0 20px 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      .gift-percentage-bar{
        width: 341px;
        height: 6px;
        border: 1px solid #3b3b3b;
        border-radius: 4px;
        span{
          display: block;
          width: 66%;
          height: 100%;
          border-radius: 3px;
          background: #970e0f;
        }
      }
      .gift-list-percentage{
        margin-top: 10px;
        color: #cecece;
      }
      .get-code-btn{
        width: 94px;
        height: 32px;
        background: #970e0f;
        font-size: 18px;
        color: #ffffff;
        border: none;
        text-align: center;
        line-height: 30px;
        letter-spacing: 2px;
        position: absolute;
        bottom: 23px;
        right: 27px;
        cursor: pointer;
        outline: none;
        user-select: none;
      }
      .get-code-btn:after{
        content: "";
        display: block;
        position: absolute;
        width: 94px;
        height: 32px;
        top: 0;
        left: 0;
        pointer-events: none;
        //设置径向渐变
        background-image: radial-gradient(circle, rgb(141, 133, 133) 10%, transparent 10.01%);
        background-repeat: no-repeat;
        background-position: 50%;
        transform: scale(10, 10);
        opacity: 0;
        transition: transform .3s, opacity .5s;
      }
      .get-code-btn:active:after{
       transform: scale(0, 0);
        opacity: .3;
        //设置初始状态
        transition: 0s;
      }
    }
    .gift-list-item:nth-child(2n){
      width: 548px;
      margin-left: 20px;
    }
    .gift-list-item:nth-child(1){
      @include bg('../../assets/images/gift-list-bg1.png');
    }
    .gift-list-item:nth-child(2){
      @include bg('../../assets/images/gift-list-bg2.png');
    }
    .gift-list-item:nth-child(3){
      @include bg('../../assets/images/gift-list-bg3.png');
    }
    .gift-list-item:nth-child(4){
      @include bg('../../assets/images/gift-list-bg2.png');
    }
    .gift-list-item:nth-child(5){
      @include bg('../../assets/images/gift-list-bg4.png');
    }
    .gift-list-item:nth-child(6){
      @include bg('../../assets/images/gift-list-bg5.png');
    }
  }
  .modal-cover{
    background: none;
    .modal-content{
      width: 640px;
      height: 406px;
      background: rgba(0,0,0, .45);
      .close{
        width: 20px;
        height: 20px;
        position: absolute;
        top: 28px;
        right: 31px;
        cursor: pointer;
        @include bg('../../assets/images/close.png');
      }
      .modal-bg{
        width: 548px;
        height: 313px;
        margin: 47px auto;
        position: relative;
        padding: 104px 90px 0 104px;
        font-size: 20px;
        text-align: initial;
        @include bg('../../assets/images/gift-modal-bg.png');
        p{
          line-height: 26px;
          span{
            color: #970e0f;
          }
        }
      }
      .code-box{
        width: 351px;
        height: 38px;
        border: 1px solid #a0a0a0;
        margin-top: 45px;
        span{
          display: block;
          float: left;
          line-height: 37px;
        }
        >span:nth-child(1){
          width: 240px;
          height: 38px;
          padding: 0 16px;
          text-align: initial;
          overflow: hidden;
        }
        >span:nth-child(2){
          width: 78px;
          height: 38px;
          text-align: center;
          border-left: 1px solid #a0a0a0;
          user-select: none;
          cursor: pointer;
          background: linear-gradient(to bottom,#4d0708,#1e0303);
        }
      }
    }
  }
}
</style>
