<?php
	include('API.php');
	function __autoload($class) {
		include($class.'.php');
	}
	$template = array(
						'touser'=>'osZ34wolnrTEeOwt9mqSED3HPmeE',
						'template_id'=>'ZbcaqeeDxtmxLmfU9eINCyai4i5gbgIYc_FkV2WXq-U',
						'url'=>'http://www.baidu.com',
						'topcolor'=>'#7B68EE',
						'data'=>array(
										'first'=>array('value'=>urlencode('测试_标题')),
										'peoduct'=>array('value'=>urlencode('测试'), 'color'=>'#FF0000'),
										'price'=>array('value'=>urlencode('7.00元'), 'color'=>'#c4c400'),
										'time'=>array('value'=>urlencode('2014年6月1日'), 'color'=>'#0000FF'),
										'remark'=>array('value'=>urlencode('\\n测试_结束语')),
						)
	);
	$acc = new access_token( '4297f44b13955235245b2497399d7a93.xml' );
	$access_token = $acc;
	$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
	$res = new http_request();
	$data = $res->http_request_POST( $url, urldecode(json_encode($template)));
	var_dump($data);
?>