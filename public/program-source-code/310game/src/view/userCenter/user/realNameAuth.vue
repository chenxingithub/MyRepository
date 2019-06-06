<template>
  <div class="real-name-auth">
    <p class="tit">实名认证</p>
    <div class="name-auth-con bor-box" v-if="AuthInfo && userInfo">
      <p>您的账号：<span>{{  AuthInfo.user_name }}</span></p>
      <div class="unverified" v-if="!userInfo.real_name">
        <p><label>真实姓名，如：张三</label></p>
        <input type="text" placeholder="请输入您的姓名" v-model="name">
        <p><label>身份证号码如：440106198101010155</label></p>
        <input type="text" placeholder="请输入您的身份证号码" v-model="id">
        <p>
          <button @click="authName">提 交</button>
        </p>
      </div>
      <div class="authenticated" v-if="userInfo.real_name">
        <p>真实姓名：<span>{{ handleRealname(userInfo.real_name) }}</span></p>
        <p>身份证号码：<span>{{ handleRealid(userInfo.real_id) }}</span></p>
        <router-link to="uAdmin" tag="button">返 回</router-link>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from 'vuex'
import { req_authName } from '../../../api/index.js'
import { validateRealName, validateIdNum } from '../../../utils/validate.js'

export default {
  name: 'realNameAuth',
  data() {
    return {
      auth: false,
      name: '',
      id: ''
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
    authName() {
      let nameVal = validateRealName(this.name);
      let idVal = validateIdNum(this.id);

      if(nameVal.type != 1) {
        this.tipModalAct({msg: nameVal.msg, type: 'fail'});
        return false;
      }
      if(idVal.type != 1) {
        this.tipModalAct({msg: idVal.msg, type: 'fail'});
        return false;
      }

      req_authName(this.AuthInfo.access_token,this.name, this.id)
        .then(res => {
          let data = res.data;
          if(data.ret == 1) {
            this.saveUserInfo({real_id: this.id, real_name: this.name});
            this.tipModalAct({msg: '认证成功！', type: 'success'});
          } else {
            this.tipModalAct({msg: data.msg, type: 'fail'});
          }
        })
    },
    handleRealname(name) {
      let nameLen = name.length;

      if(nameLen == 2) {
        return '*'+name.substr(1);
      }
      if(nameLen >= 3) {
        return '**'+name.substr(2);
      }

      return name;
    },
    handleRealid(id) {
      return id.slice(0,6) + '********' + id.slice(14,18);
    }
  }
}
</script>
<style lang="scss">
@import './style.scss';

.real-name-auth{
  @include tit;
  color: #656565;
  input{
    width: 527px;
    height: 39px;
    border: 1px solid #d9d9d9;
    border-radius: 4px;
    padding-left: 12px;
  }
  .name-auth-con{
    padding: 0 0 0 20px;
    >p{
      margin: 38px 0 29px;
    }
  }
  .unverified{
    p{
      margin-bottom: 10px;
    }
    input{
      margin-bottom: 20px;
    }
    button{
      @include sub-btn;
    }
  }
  .authenticated{
    >p{
      margin-bottom: 20px;
      >span{
        color: $color-b;
      }
    }
    button{
      @include sub-btn;
      margin: 140px 0 0 296px;
    }
  }
}
</style>
