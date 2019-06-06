<template>
  <div class="modify-password">
    <p class="tit">修改密码</p>
    <div class="modify-pwd-con bor-box">
      <p class="account">您的账号：<span>{{AuthInfo.user_name}}</span></p>
      <!-- <p class="safety-tip"><img src="../../../assets/safety-m.png" alt="bindtel-icon">为了您的账号安全，请绑定手机号码</p> -->
      <p>输入当前密码</p>
      <input type="password" maxlength="20" v-model="cur_pwd">
      <p>输入新密码</p>
      <input type="password" maxlength="20" placeholder="密码为6~20位字母和数字组成" v-model="new_pwd">
      <p>再次输入新密码</p>
      <input type="password" maxlength="20" v-model="comfirm_pwd" @keyup.enter="modifyPwd">
      <button class="modify-pwd-btn" @click="modifyPwd">
        提 交
      </button>
    </div>
  </div>
</template>
<script>
import { validatePassword } from '../../../utils/validate.js'
import { req_modfPwd } from '../../../api/index.js'
import { mapState, mapActions, mapMutations } from 'vuex'

export default {
  name: 'modifyPassword',
  data() {
    return {
      cur_pwd: '',
      new_pwd: '',
      comfirm_pwd: ''
    }
  },
  computed: {
    ...mapState([
      'AuthInfo'
    ])
  },
  methods: {
    ...mapActions([
      'tipModalAct'
    ]),
    ...mapMutations([
        'handleLogout',
        'exchangeLogRegModal'
    ]),
    initInputVal() {
      this.cur_pwd = '';
      this.new_pwd = '';
      this.comfirm_pwd = '';
    },
    modifyPwd() {
      let curPwdVal = validatePassword(this.cur_pwd);
      let newPwdVal = validatePassword(this.new_pwd);
      let comfirmPwdVal = validatePassword(this.comfirm_pwd);
      if(curPwdVal.type != 1) {
        this.tipModalAct({msg: curPwdVal.msg.replace(/密码/, '当前密码'), type: 'fail'});
        return false;
      }

      if(newPwdVal.type != 1) {
        this.tipModalAct({msg: newPwdVal.msg.replace(/密码/, '新密码'), type: 'fail'});
        return false;
      }

      if(this.new_pwd != this.comfirm_pwd) {
        this.tipModalAct({msg: '前后新密码不一致！', type: 'fail'});
        return false;
      }

      req_modfPwd(
        this.AuthInfo.user_name,
        this.cur_pwd,
        this.new_pwd,
        this.AuthInfo.access_token)
      .then(res => {

        let data = res.data;
        if(data.ret == 1) {

          this.tipModalAct({msg: '密码修改成功！', type: 'success'});
          this.storejs.set('user', '');
          this.handleLogout();
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
@import './style.scss';

.modify-password{
  @include tit;
  font-size: 18px;
  .modify-pwd-con{
    padding-left: 20px;
    p{
      margin: 26px 0 0;
      color: #8a8a8a;
    }
    .safety-tip{
      color: #ef4747;
      img{
        margin-right: 5px;
        vertical-align: text-bottom;
      }
    }
    input{
      width: 525px;
      height: 37px;
      margin-top: 10px;
      padding-left: 5px;
      border: 1px solid #d9d9d9;
      border-radius: 4px;
    }
    .modify-pwd-btn{
      @include sub-btn;
    }
  }
  .account{

    span{
      color: #6abce8;
    }
  }
}
</style>
