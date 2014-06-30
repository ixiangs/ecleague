<?php
$cookie_jar = tempnam('./tmp','cookie');
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, 'http://www.ppc52.com/checkuserlogin.asp');
curl_setopt($ch, CURLOPT_POST, 1);
$request = 'nickname=guaishoububu128&userpassword=ppc52com1';
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
//把返回来的cookie信息保存在$cookie_jar文件中
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
//设定返回的数据是否自动显示
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//设定是否显示头信息
curl_setopt($ch, CURLOPT_HEADER, true);
//设定是否输出页面内容
//curl_setopt($ch, CURLOPT_NOBODY, true);
$result = curl_exec($ch);
curl_close($ch);

echo mb_convert_encoding($result, 'GB18030');

