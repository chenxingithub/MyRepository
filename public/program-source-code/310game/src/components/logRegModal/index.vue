<template>
  <div id="logReg" class="modal-cover" v-if="logRegModalType">
    <div class="modal-content">
      <!-- 登录 -->
      <div class="login" v-if="logRegModalType == 'login'">
        <div class="tit">
          用户登录
          <div class="close-btn" @click="handleModal('close')"></div>
        </div>
        <div class="content bor-box">
          <div class="account input-box">
            <label><img src="../../assets/user-icon.png" alt=""></label>
            <input autofocus="autofocus" type="text" placeholder="请输入账号"
              v-model="account" @focus="handleInputFocus"
              onkeyup="value=value.replace(/[^A-Za-z0-9]/ig,'')" maxlength="14">
          </div>
          <div class="password input-box">
            <label><img src="../../assets/password-icon.png" alt=""></label>
            <input type="password" placeholder="请输入密码" v-model="password"
              @focus="handleInputFocus"
              @keyup.enter="login"
              maxlength="14">
          </div>
          <div class="func-box clearfix">
            <div @click="selectedAutoLogin">
              <span class="auto-log-btn">
                <img v-if="autoLogin" src="../../assets/selected-icon.png" alt="selected">
              </span>
              <span>下次自动登录</span>
            </div>
            <div>
              <span @click="handleModal('modifyPwd')">忘记密码？</span>
            </div>
          </div>
          <p class="err-tip">{{ errMsg }}</p>
          <div class="login-btn sub-btn" @click="login">
            <span>登</span><span>录</span>
          </div>
          <div class="reg-tip" v-if="!errMsg">
            没有账号？请<span @click="handleModal('register')">注册</span>
          </div>
        </div>
      </div>
      <!-- 注册 -->
      <div class="register" v-if="logRegModalType == 'register'">
        <div class="tit">
          快速注册
          <div class="close-btn" @click="handleModal('close')"></div>
        </div>
        <div class="content bor-box">
          <div class="account input-box">
            <input autofocus="autofocus" type="text" placeholder="账号（由6~14位字母和数字组成）" v-model="account"
              @focus="inputFocusCheck('')"
              onkeyup="value=value.replace(/[^A-Za-z0-9]/ig,'')" maxlength="14">
          </div>
          <div class="password input-box">
            <input type="password" placeholder="密码（密码为6~20位字母和数字组成）" v-model="password"
              @focus="inputFocusCheck('')" maxlength="20">
          </div>
          <div class="password input-box">
            <input type="password" @focus="handleInputFocus" placeholder="确认密码" v-model="confirmPassword"
              maxlength="20">
          </div>
          <div class="tel input-box">
            <input type="text" placeholder="手机号码" v-model="tel" @focus="handleInputFocus"
              onkeyup="value=value.replace(/[^0-9]/g,'')" maxlength="11">
          </div>
          <div class="tel-check-code input-box clearfix">
            <input type="text" placeholder="手机验证码" v-model="tel_check_code"
              @focus="handleInputFocus"
              onkeyup="value=value.replace(/[^0-9]/g,'')" maxlength="6">
            <button @click="getTelCode('smsreg')" ref="getTelCode">获取验证码</button>
          </div>
          <div class="real-name input-box">
            <input type="text" placeholder="真实姓名" @focus="handleInputFocus" v-model="real_name" maxlength="20">
          </div>
          <div class="ID-num input-box">
            <input type="text" placeholder="身份证号码" v-model="id_num" @keyup.enter="register"
              @focus="handleInputFocus" onkeyup="value=value.replace(/[^x0-9]/ig,'')" maxlength="18">
          </div>
          <!-- <div class="auth-code-box input-box clearfix">
            <input type="text" maxlength="4" v-model="auth_code" @keyup.enter="register"
              onkeyup="value=value.replace(/[^A-Za-z0-9]/g,'')">
            <span @click="reFreshCheckCodeUrl">
              <img src="../../assets/refresh-icon.png" alt="refresh">
            </span>
            <div class="uth-code">
              <img :src="checkCodeImgUrl"
              onclick="this.src='https://www.310game.com/user?action=captcha&r='+Math.random()" alt="code">
            </div>
          </div> -->
          <div class="protocol-box">
            <span class="protocol-btn" @click="selectedProtocol">
                <img v-if="protocolSel" src="../../assets/selected-icon.png" alt="selected">
            </span>
            <a href="https://www.310game.com/user/protocol" target="_blank">《冰鸟网络通行证用户协议》</a>
          </div>
          <p class="err-tip">{{ errMsg }}</p>
          <div class="sub-btn register-btn" @click="register">
            快速注册
          </div>
          <div class="reg-tip" v-if="!errMsg">
            已注册，请<span @click="handleModal('login')">登录</span>
          </div>
        </div>
      </div>

      <!-- 修改密码 -->
      <div class="modifyPwd" v-if="logRegModalType == 'modifyPwd'">
        <div class="tit">
          修改密码
          <div class="close-btn" @click="handleModal('close')"></div>
        </div>
        <div class="content bor-box">
          <div class="tel input-box">
            <input type="text" placeholder="手机号码" v-model="tel"
              @focus="handleInputFocus" AUTOCOMPLETE="off"
              onkeyup="value=value.replace(/[^0-9]/g,'')" maxlength="11">
          </div>
          <div class="tel-check-code input-box clearfix">
            <input type="text" placeholder="手机验证码" v-model="tel_check_code"
              @focus="handleInputFocus" AUTOCOMPLETE="off"
              onkeyup="value=value.replace(/[^0-9]/g,'')" maxlength="6">
            <button ref="getTelCode" @click="getTelCode('reset')">获取验证码</button>
          </div>
          <div class="password input-box">
            <input type="password" placeholder="密码（密码为6~20位字母和数字组成）" v-model="password"
              @focus="handleInputFocus" maxlength="20">
          </div>
          <div class="password input-box">
            <input type="password" placeholder="确认密码" v-model="confirmPassword"
              @focus="handleInputFocus" @keyup.enter="modifyPassword" maxlength="20">
          </div>
          <p class="err-tip">{{ errMsg }}</p>
          <a href="https://www.310game.com/newcms2/customer-service/#/RetrievePassword"
            v-if="!errMsg" target="_blank" class="pwd-apply">忘记/没有绑定手机号？请点击这里</a>
          <div class="sub-btn modifyPwd-btn" @click="modifyPassword">确认修改</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import { timeDown } from '../../utils/utils.js'
import md5 from 'js-md5'
import { req_register, req_sendCode, req_userInfo, req_login, req_modifyPwd } from '../../api/index.js'

import {
  validateAccount,
  validatePassword,
  validateTel,
  validateTelCheckCode,
  validateRealName,
  validateIdNum,
  validataAuthCode } from '../../utils/validate.js'

export default {
  name: "logReg",
  data() {
    return {
      type: 'register',// login/register/modifyPwd/''
      // autoLogin: true, //自动登录
      protocolSel: true,// 注册协议
      checkCodeImgUrl: 'https://www.310game.com/user?action=captcha',//验证码图片url
      errMsg: '',
      account: '',
      password: '',
      confirmPassword: '',
      tel: '',
      tel_check_code: '',
      real_name: '',
      id_num: '',
      auth_code: ''
    }
  },
  computed: {
    ...mapState([
      'logRegModalType',
      'autoLogin',
      'AuthInfo'
    ]),
  },
  methods: {
    ...mapMutations([
      'exchangeLogRegModal',
      'handleLoginAuto',
      'saveAuthInfo',
      'saveUserInfo'
    ]),
    ...mapActions([
      'tipModalAct'
    ]),
    selectedAutoLogin() {//勾选自动登录
      this.handleLoginAuto(!this.autoLogin);
    },
    selectedProtocol() {//勾选同意协议
      this.protocolSel = !this.protocolSel;
      this.errMsg = '';
    },
    handleModal(type) {
      this.initInputVal();

      if(type == 'close') {//关闭弹窗
        this.exchangeLogRegModal('');
        this.initInputVal();
        this.errMsg = '';
      } else {
        this.exchangeLogRegModal(type);
      }
    },
    handleInputFocus() {
      this.errMsg = '';
    },
    getTelCode(type) {//获取验证码
      if(!this.inputFocusCheck('tel')) {
        return false;
      }

      req_sendCode(this.tel, type).then((res) => {
        // console.log(res);
        if(res.data.ret == 1) {
          timeDown(60, this.$refs.getTelCode);
          this.tipModalAct({msg: '短信发送成功！', type: 'success'});
        } else {
          this.tipModalAct({msg: res.data.msg, type: 'fail'});
        }
      });

    },
    initInputVal() {
      this.errMsg = '';
      this.account = '';
      this.password = '';
      this.confirmPassword = '';
      this.tel = '';
      this.tel_check_code = '';
      this.real_name = '';
      this.id_num = '';
    },
    reFreshCheckCodeUrl() {
      this.checkCodeImgUrl = this.checkCodeImgUrl + '&r=' + Math.random();
    },
    inputFocusCheck(type) {
      if(!type) {
        this.errMsg = '';
        return false;
      }
      if(type == 'account') {
        let accountData = validateAccount(this.account);
        if(accountData.type != 1) {
          this.errMsg = accountData.msg;
          return false;
        }
      }
      if(type == 'password') {
        let passwordData = validatePassword(this.password);
        if(passwordData.type != 1) {
          this.errMsg =passwordData.msg;
          return false;
        }
      }
      if(type == 'tel') {
        let telData = validateTel(this.tel);
        if(telData.type != 1) {
          this.errMsg =telData.msg;
          return false;
        }
      }
      if(type == 'telCheckCode') {
        let telCheckCodeData = validateTelCheckCode(this.tel_check_code);
        if(telCheckCodeData.type != 1) {
          this.errMsg =telCheckCodeData.msg;
          return false;
        }
      }
      if(type == 'realName') {
        let realNameData = validateRealName(this.real_name);
        if(realNameData.type != 1) {
          this.errMsg =realNameData.msg;
          return false;
        }
      }
      if(type == 'idNum') {
        let idNumData = validateIdNum(this.id_num);
        if(idNumData.type != 1) {
          this.errMsg =idNumData.msg;
          return false;
        }
      }
      if(type == 'authCode') {
        let authCodeData = validataAuthCode(this.auth_code);
        if(!this.protocolSel) {
          this.errMsg ='请勾选同意《冰鸟网络通行证用户协议》';
          return false;
        }
      }

      return true;
    },
    login() {
      if(!this.inputFocusCheck('account')) { return false; };
      if(!this.inputFocusCheck('password')) { return false; };

      // console.log(11); JSSDK.md5(pwd + JSSDK.md5(pwd));
      req_login(this.account, this.password)
        .then(res => {
          let data = res.data;
          if(data.ret == 1) {
            if(this.autoLogin) {
              this.storejs.set('user', {...data.content});
            }
            this.initInputVal();
            this.exchangeLogRegModal('');
            this.tipModalAct({msg: '登录成功!', type: 'success'});
            this.saveAuthInfo(data.content);
          } else {
            this.tipModalAct({msg: data.msg, type: 'fail'});
          }

          return res;
        }).then(res => {//返回成功后请求用户信息
          let data = res.data;
          if(data.ret == 1) {
            req_userInfo(this.AuthInfo.user_name, this.AuthInfo.access_token)
              .then(res => {
                if(res.data.ret == 1) {
                  this.saveUserInfo(res.data.content);
                } else {
                  this.saveUserInfo('');
                  this.tipModalAct({msg: res.data.msg, type: 'fail'});
                }
              });
          }

        });
    },
    register() {//点击注册，校验数据，通过提交
      let accountData = validateAccount(this.account);
      let passwordData = validatePassword(this.password);
      let telData = validateTel(this.tel);
      let telCheckCodeData = validateTelCheckCode(this.tel_check_code);
      let realNameData = validateRealName(this.real_name);
      let idNumData = validateIdNum(this.id_num);
      // let authCodeData = validataAuthCode(this.auth_code);

      // console.log(this.tel_check_code);

      if(accountData.type != 1) {
        this.errMsg = accountData.msg;
        return false;
      }
      if(passwordData.type != 1) {
        this.errMsg =passwordData.msg;
        return false;
      }
      if(this.confirmPassword != this.password) {
        this.errMsg = '两次密码不一致！';
        return false;
      }
      if(telData.type != 1) {
        this.errMsg =telData.msg;
        return false;
      }
      if(telCheckCodeData.type != 1) {
        this.errMsg =telCheckCodeData.msg;
        return false;
      }
      if(realNameData.type != 1) {
        this.errMsg =realNameData.msg;
        return false;
      }
      if(idNumData.type != 1) {
        this.errMsg =idNumData.msg;
        return false;
      }

      if(!this.protocolSel) {
        this.errMsg ='请勾选同意《冰鸟网络通行证用户协议》';
        return false;
      }

      req_register(this.account, this.password, this.tel, this.tel_check_code, this.real_name, this.id_num)
        .then(res => {

          let token,expires,user_id,user_name;
          if(res.data.ret == 1) {//注册成功
            this.storejs.set('user', res.data.content);
            this.exchangeLogRegModal('');//关闭注册框
            this.initInputVal();//
            this.tipModalAct({msg: '注册成功！', type: 'success'});
            this.saveAuthInfo(res.data.content);
          } else {
            this.tipModalAct({msg: res.data.msg, type: 'fail'});
          }

          return res;
        })
        .then(res => {//注册成功后获取用户信息
          // console.log(res);
          let data = res.data;

          if(data.ret != 1) { return false; }//判断是否注册成功

          req_userInfo(data.content.user_name,  data.content.access_token)
            .then(res => {
              // console.log(res);

              if(res.data.ret == 1) {
                this.saveUserInfo(res.data.content);
              } else {
                this.saveUserInfo('');
                this.tipModalAct({msg: res.data.msg, type: 'fail'});
              }
            });
        });
    },
    modifyPassword() {
      let telData = validateTel(this.tel);
      let telCheckCodeData = validateTelCheckCode(this.tel_check_code);

      if(telData.type != 1) {
        this.errMsg = telData.msg;
        return false;
      }
      if(telCheckCodeData.type != 1) {
        this.errMsg = telCheckCodeData.msg;
        return false;
      }
      if(this.password.length < 6) {
        this.errMsg = '请输入正确密码！';
        return false;
      }
      if(this.confirmPassword != this.password) {
        this.errMsg = '前后密码不一致！';
        return false;
      }

      req_modifyPwd(this.tel, this.tel_check_code, this.password)
        .then(res => {
          let data = res.data;

          if(data.ret == 1) {
            this.tipModalAct({msg: '密码修改成功！', type: 'success'});
            this.initInputVal();
            this.exchangeLogRegModal('login');
          } else {
            this.tipModalAct({msg: data.msg, type: 'fail'});
          }
        });

    }
  }
}
</script>
<style lang="scss">
#logReg{
  background: rgba(1,2,3, 0.8);
  .modal-content{
    background: #ffffff;
    width: 408px;
    min-height: 371px;
    border-radius: 4px;
  }
  .tit{
    width: 100%;
    height: 59px;
    line-height: 58px;
    font-size: 26px;
    text-align: center;
    background: #36afec;
    border-radius: 4px;
    color: #ffffff;
    position: relative;
    user-select: none;
    .close-btn{
      position: absolute;
      top: 21px;
      right: 24px;
      width: 18px;
      height: 20px;
      cursor: pointer;
      background: url('../../assets/close-btn.png') no-repeat;
      background-size: 100% 100%;

    }
  }
  .sub-btn{
    width: 100%;
    height: 49px;
    line-height: 48px;
    border-radius: 5px;
    text-align: center;
    font-size: 20px;
    color: #ffffff;
    background: #37b0ee;
    box-shadow: 1px 1px 8px #37b0ee;
    user-select: none;
    cursor: pointer;
    span{
      margin: 0 5px;
    }
  }
  .content{
    font-size: 14px;
    padding: 40px 50px 0;
  }
  .input-box{
    width: 308px;
    height: 41px;
    margin: 0 auto 15px;
    background: #f2f2f2;
    >label{
      width: 36px;
      // text-align: center;
      display: inline-block;
      >img{
        margin: 0 15px;
      }
    }
    >input{
      width: 267px;
      height: 100%;
      border: none;
      outline: none;
      box-sizing: border-box;
      border-radius: 2px;
      // padding-left: 5px;
      background: transparent;
    }
  }
  .reg-tip{
    color: #778699;
    text-align: center;
    margin-bottom: 29px;
    user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    span{
      color: #31aae2;
      cursor: pointer;
    }
  }
  .tel-check-code{
    background: #ffffff;
    >input{
      width: 196px;
      float: left;
      background: #f2f2f2;
    }
    >button{
      width: 100px;
      height: 39px;
      line-height: 40px;
      border: 1px solid #98d4f0;
      color: #31aae2;
      text-align: center;
      float: right;
      user-select: none;
      cursor: pointer;
      border-radius: 2px;
      background: #f2f2f2;
    }
  }
  .err-tip{
    // height: 27px;
    width: 100%;
    text-align: center;
    color: #ff0000;
  }

  .login{
    .func-box{
      width: 308px;
      height: auto;
      margin: 0 auto;
      color: #778699;
      user-select: none;
      .auto-log-btn{
        width: 12px;
        height: 12px;
        display: inline-block;
        vertical-align: middle;
        border: 1px solid #778699;
        text-align: center;
        box-sizing: border-box;
        border-radius: 2px;
        >img{
          vertical-align: super;
        }
      }
      >div:nth-child(1){
        float: left;
        cursor: pointer;
      }
      >div:nth-child(2){
        float: right;
        cursor: pointer;
      }
    }
    .login-btn{
      margin: 0 0 16px;
      span{
        margin: 0 5px;
      }
    }
    .err-tip{
      margin: 32px 0 20px;
    }
  }
  .register{
    input{
      padding-left: 14px;
    }
    .login-btn{
      width: 100%;
    }
    .auth-code-box{
      background: #ffffff;
      >input{
        width: 148px;
        height: 41px;
        float: left;
        background: #f2f2f2;
      }
      .uth-code{
        float: right;
        width: 118px;
        height: 41px;
        user-select: none;
        cursor: pointer;
        background: #f2f2f2;
        border-radius: 2px;
        img{
          width: 100%;
          height: 100%;
        }
      }
      >span{
        float: right;
        display: block;
        width: 32px;
        height: 41px;
        cursor: pointer;
        text-align: center;
        >img{
          width: 16px;
          height: 15px;
          margin: 12px 0;
        }
      }
    }
    .protocol-box{
      cursor: pointer;
      user-select: none;
      color: #778699;
      .protocol-btn{
        width: 12px;
        height: 12px;
        display: inline-block;
        vertical-align: middle;
        border: 1px solid #778699;
        text-align: center;
        box-sizing: border-box;
        border-radius: 2px;
        >img{
          vertical-align: super;
        }
      }
    }
    .register-btn{
      margin: 0 0 16px;
    }
    .err-tip{
      margin: 24px 0 18px;
    }
  }
  .modifyPwd{
    input{
      padding-left: 5px;
    }
    .modifyPwd-btn{
      margin: 0 0 37px;
    }
    .err-tip{
      margin: 10px 0 15px;
    }
    .pwd-apply{
      display: block;
      margin: 10px 0 15px;
      text-align: center;
      color: #31aae2;
      user-select: none;
      cursor: pointer;
    }
  }

}
</style>
