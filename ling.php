<?php
// 创建一个cURL资源
while(true){
    $ch = curl_init();

    // 设置URL和相应的选项
    curl_setopt($ch, CURLOPT_URL, "http://post.shundecity.com:81/mxds2/clickgood.php");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "id=650");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept'=>'*/*',
    'Accept-Encoding'=>'gzip,deflate,sdch',
    'Accept-Language'=>'zh-CN,zh;q=0.8,ja;q=0.6,tr;q=0.4,zh-TW;q=0.2,en;q=0.2',
    'Connection'=>'keep-alive',
    'Content-Length'=>'6',
    'Content-type'=>'application/x-www-form-urlencoded',
    'Cookie'=>'mxdsgood650=mxdsgood; CNZZDATA5068492=cnzz_eid%3D243469228-1399886041-%26ntime%3D1400218257%26cnzz_a%3D14%26ltime%3D1400206928164%26rtime%3D2',
    'DNT'=>'1',
    'Host'=>'post.shundecity.com:81',
    'Origin'=>'http=>//post.shundecity.com:81',
    'Referer'=>'http://post.shundecity.com:81/mxds2/page.php?id=650',
    'User-Agent'=>'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36'
    ));
    // 抓取URL并把它传递给浏览器
    $result = curl_exec($ch);

    // 关闭cURL资源，并且释放系统资源
    curl_close($ch);
    print 'post at:'.date('Y-m-d h:i:s').' count:'.$result."\n";
    $m = mt_rand(1, 10);

    sleep($m * 60);
}