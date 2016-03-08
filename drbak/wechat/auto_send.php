<?php 
	include('API.php');
	function __autoload($class) {
		include($class.'.php');
	}
	$acc = new access_token( '4297f44b13955235245b2497399d7a93.xml' );
	$access_token = $acc;
	$url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $access_token;
	$res = new http_request();
	$data = $res->http_request_GET( $url );
	$data = json_decode( $data, true );
	$air = air_quality( '北京' );
	$weather = weather();
	foreach( $data['data']['openid'] as $openid ) {
		$send = new send_msg( $openid );
		$nickname = get_user_nickname( $openid );
		$msg = 'Hi   ' . $nickname . "   早上好 !\n\n" . $air . "\n\n" . $weather . "\n更多详情请参考自己手机app";
		$send->send( $msg );
	}
	/*取得用户昵称*/
	function get_user_nickname($openid) {
		$acc = new access_token('4297f44b13955235245b2497399d7a93.xml');
		$access_token = $acc;
		$get_nickname = new http_request();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
		$nickname_json = $get_nickname->http_request_GET($url);
		$nickname_array = json_decode($nickname_json, true);
		return $nickname_array['nickname'];
	}
 ?>