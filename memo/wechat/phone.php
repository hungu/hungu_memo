<?php 
	$ch = curl_init();
	$content = urlencode( '【昌居园】您的验证码为:111111');
    $url = 'http://apis.baidu.com/kingtto_media/106sms/106sms?mobile=18810666064&content=' . $content;

    $header = array(
        'apikey: e37a57971cd71b49a418b197ab43a40a',
    );
    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);

    var_dump(json_decode($res));
 ?>