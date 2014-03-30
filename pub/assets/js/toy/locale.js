Toy.Locale = {
    languages : {},
    current : null,
    use : function(lang) {
        Toy.Locale.current = lang;
    },
    define : function(lang, group, texts) {
        if (!Object.keys(Toy.Locale.languages).contains(lang)) {
            Toy.Locale.languages[lang] = {};
        }

        Toy.Locale.languages[lang][group] = texts;
    },
    get : function(name) {
        var arr = name.split('.');
        var cur = Toy.Locale.current;
        if(Toy.Locale.languages[cur]){
            if(Toy.Locale.languages[cur][arr[0]]){
                if(Toy.Locale.languages[cur][arr[0]][arr[1]]){
                    return Toy.Locale.languages[cur][arr[0]][arr[1]];
                }
            }
        }
        return '';
    }
};

Toy.Locale.define('zh-CN', 'Validate', {
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
Toy.Locale.define('zh-CN', 'Default', {
	delete_confirm: '确认删除',
	delete_confirm_msg: '数据删除后不能恢复，确定删除吗？',
    please_select_row: '请选择要操作的数据',
	cancel: '取消',
	'delete': '删除'
});
Toy.Locale.use('zh-CN');