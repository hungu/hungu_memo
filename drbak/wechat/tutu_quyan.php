<?php
	include('API.php');
	function __autoload($class) {
		include($class.'.php');
	}
	$template = array(
						'touser'=>'osZ34wm4CqnwTQfKGJHZ5fNSCQNE',
						'template_id'=>'W-Xmbf5Yf_-nJfm0OvPPMy8kjl9MIybDHspu6O03suU',
						'url'=>'http://tubak.zuixinan.top',
						'topcolor'=>'#7B68EE',
						'data'=>array(
									'first'=>array('value'=>urlencode('给马野发倒回\n给屈妍发大表\n'), 'color'=>'#FF0000'),
									'hotelName'=>array('value'=>urlencode('今日')),
									'roomName'=>array('value'=>urlencode('给马野发倒回\n给屈妍发大概\n')),
									'remark'=>array('value'=>urlencode('\\n亲爱的,别忘了!!!')),
						)
	);
	$acc = new access_token( '4297f44b13955235245b2497399d7a93.xml' );
	$access_token = $acc;
	$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
	$res = new http_request();
	$data = $res->http_request_POST( $url, urldecode(json_encode($template)));
	var_dump($data);
?>