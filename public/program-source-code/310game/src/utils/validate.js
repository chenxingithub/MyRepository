const validateAccount = str => { //校验账号
  str = String(str).trim();
  let len = str.length,
  data = {type: 0, msg: ''},
  reg = /[^A-Za-z0-9]/g;

  if(len == 0) {
    data.msg = '请输入账号！';
    return data;
  }else if(len < 6) {
    data.msg = '账号位数至少6位！';
    return data;
  } else if(len > 14) {
    data.msg = '您输入的账号位数大于14位！';
    return data;
  }

  if(reg.test(str)) {
    data.msg = '账号只能由字母和数字组成,请重新输入！';
    return data;
  }

  data = {type: 1, msg: '账号验证通过'};//验证通过
  return data;
}

const validatePassword = str => {//密码校验
  str = String(str).trim();
  let len = str.length,
  data={type: 0, msg: ''},
  reg = /[^A-Za-z0-9]/g;

  if(len == 0) {
    data.msg = '请输入密码！';
    return data;
  }else if(len < 6) {
    data.msg = '密码位数至少6位！';
    return data;
  } else if(len > 20) {
    data.msg = '您输入的密码位数大于20位，重新输入！';
    return data;
  }

  if(reg.test(str)) {
    data.msg = '密码只能由字母和数字组成，请重新输入！';
    return data;
  }

  data = {type: 1, msg: '密码验证通过'};//验证通过
  return data;
}

const validateTel = str => {
  str = String(str).trim();
  let len = str.length,
  data={type: 0, msg: ''},
  reg = /^[1][3,4,5,7,8][0-9]{9}$/g;

  if(len == 0) {
    data.msg = '请输入您的手机号码！';
    return data;
  }
  if(len != 11 || !reg.test(str)) {
    data.msg = '请输入正确的手机号码！';
    return data;
  }

  data = {type: 1, msg: '手机号验证通过'};//验证通过
  return data;
}

const validateTelCheckCode = str => {//手机验证码校验
  str = String(str).trim();
  let len = str.length,
  data={type: 0, msg: ''},
  reg = /[^0-9]/g;

  if(len == 0) {
    data.msg = '请输入手机验证码！';
    return data;
  }

  if(len != 6 || reg.test(str)) {
    data.msg = '您的手机验证码有误，请重新输入！';
    return data;
  }

  data = {type: 1, msg: '手机验证码验证通过'};//验证通过
  return data;
}

const validateRealName = str => {//真实姓名校验
  str = String(str).trim();
  let len = str.length,
  data={type: 0, msg: ''};

  if(len == 0) {
    data.msg = '真请输入您的真实姓名！';
    return data;
  }

  data = {type: 1, msg: '真实姓名验证通过'};//验证通过
  return data;
}

const validateIdNum = str => {//身份证号校验
  str = String(str).trim();
  let len = str.length,
  data={type: 0, msg: ''},
  reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/g;

  if(len == 0) {
    data.msg = '请输入您的身份证号码！';
    return data;
  }
  if(len < 15 || !reg.test(str)) {
    data.msg = '您输入的身份证号码有误，请重新输入！';
    return data;
  }

  data = {type: 1, msg: '身份证号验证通过'};//验证通过
  return data;
}

const validataAuthCode = str => {//登录验证码
  str = String(str).trim();
  let len = str.length,
  data={type: 0, msg: ''},
  reg = /[^A-Za-z0-9]/g;

  if(len == 0) {
    data.msg = '请输入验证码！';
    return data;
  }
  if(len < 4 || reg.test(str)) {
    data.msg = '您的验证码有误，请重新输入！';
    return data;
  }

  data = {type: 1, msg: '登录验证码验证通过'};//验证通过
  return data;
}
export {
  validateAccount,
  validatePassword,
  validateTel,
  validateTelCheckCode,
  validateRealName,
  validateIdNum,
  validataAuthCode
}
