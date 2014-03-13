Locale.define('zh-CN', 'Validate', {
  required: '必填项',
  integer: '只能输入正整数',
  number: '只能输入数字',
  digits: '只能输入整数',
  email: '邮件格式有误',
  character: '只能输入英文字符、横线和下划线',
  minlength: '内容长度不能少于{len}',
  maxlength: '内容长度不能大于{len}',
  minvalue: '数值不能少于{val}',
  maxvalue: '数值不能大于{val}',
  regexp: '输入内容格式有误',
  equalto: '两次输入的内容不一致'
});
Locale.use('zh-CN');