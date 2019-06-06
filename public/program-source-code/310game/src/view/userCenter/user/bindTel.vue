<template>
  <div class="bind-tel">
    <p class="tit">绑定手机</p>
    <div class="bind-tel-con" v-if="userInfo">
      <p>您的账号：<span>{{ AuthInfo.user_name }}</span></p>
      <div v-if="!userInfo.tel_num">
        <p class="safety-tip"><img src="../../../assets/safety-m.png" alt="bindtel-icon">为了您的账号安全，请绑定手机号码</p>
        <p>
          <label>手机号</label>
          <input type="text" v-model="tel"
            onkeyup="value=value.replace(/[^0-9]/g,'')"
            maxlength="11" placeholder="请输入您要绑定的手机号码">
        </p>
        <p class="tel-code">
          <label>验证码</label>
          <input type="text" v-model="code"
            @keyup.enter="bindTel"
            onkeyup="value=value.replace(/[^0-9]/g,'')"
            maxlength="6" placeholder="短信验证码">
          <button class="get-checkcode-btn" ref="checkcode" @click="sendCode">获取验证码</button>
        </p>
        <button class="sub-btn" @click="bindTel">
          提 交
        </button>
      </div>
      <div v-if="userInfo.tel_num" class="bind">
        <p>已绑定手机号：<span>{{ userInfo.tel_num.replace(/^(\d{3})\d{5}(\d+)/,"$1*****$2") }}</span></p>
        <router-link to="uAdmin" tag="button">返 回</router-link>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from 'vuex'
import { req_bindTel, req_sendCode } from '../../../api/index.js'
import { bindTelTimeDown } from '../../../utils/utils.js'
import { validateTel, validateTelCheckCode } from '../../../utils/validate.js'

export default {
  name: 'bindTel',
  data() {
    return {
      bind: false,
      tel: '',
      code: ''
    }
  },
  computed: {
    ...mapState([
      'userInfo',
      'AuthInfo'
    ])
  },
  methods: {
    ...mapMutations([
      'saveUserInfo'
    ]),
    ...mapActions([
      'tipModalAct'
    ]),
    sendCode() {
      let telVal = validateTel(this.tel);
      if(telVal.type == 0) {
        this.tipModalAct({msg: telVal.msg, type: 'fail'});
        return false;
      }
  // bindTelTimeDown(60, this.$refs.checkcode);
  // return false;
      req_sendCode(this.tel, 'bind')
        .then(res => {

          let data = res.data;
          if(data.ret == 1) {
            this.tipModalAct({msg: data.msg, type: 'success'});
            bindTelTimeDown(60, this.$refs.checkcode);
          } else {
            this.tipModalAct({msg: data.msg, type: 'fail'});
          }
        });
    },
    bindTel() {
      let telVal = validateTel(this.tel);
      let codeVal = validateTelCheckCode(this.code);
      if(telVal.type != 1) {
        this.tipModalAct({msg: telVal.msg, type: 'fail'});
        return false;
      }
      if(codeVal.type != 1) {
        this.tipModalAct({msg: codeVal.msg, type: 'fail'});
        return false;
      }

      req_bindTel(this.tel, this.AuthInfo.user_name,this.code, this.AuthInfo.access_token, 1)
        .then(res => {
          let data = res.data;

          if(data.ret == 1) {
            this.saveUserInfo({tel_num: this.tel});
            this.tipModalAct({msg: '绑定成功！', type: 'success'});
          } else {
            this.tipModalAct({msg: data.msg, type: 'fail'});
          }
        });
    }
  }
}
</script>
<style lang="scss">
@import './style.scss';

.bind-tel{
  @include tit;
  .bind-tel-con{
    >p{
      span{
        color: $account-color;
      }
    }
  }
  .safety-tip{
    color: #ef4747;
    img{
      margin-right: 5px;
      vertical-align: text-bottom;
    }
  }
  p{
    margin: 30px 0 0;
    color: #656565;
    >label{
      margin-right: 14px;
    }
  }
  input{
    width: 525px;
    height: 37px;
    padding-left: 14px;
    border-radius: 4px;
    border: 1px solid #d9d9d9;
  }
  .tel-code{
    input{
      width: 385px;
    }
    button{
      width: 130px;
      height: 37px;
      background: $account-color;
      color: #ffffff;
      border: none;
      border-radius: 3px;
    }
  }
  .get-checkcode-btn{
    display: inline-block;
  }
  .sub-btn{
    @include sub-btn;
  }
  .bind{
    button{
      @include sub-btn;
      margin: 200px 0 0 290px;
    }
  }
}
</style>
