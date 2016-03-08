<?php 
	include('API.php');
	function __autoload($class) {
		include($class.'.php');
	}
	$acc = new access_token( '4297f44b13955235245b2497399d7a93.xml' );
	$access_token = $acc;
	$url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $access_token;
	$res = new http_request();
	$openids_json = $res->http_request_GET( $url );
	$openids_arr = json_decode( $openids_json, true );
	$openids = '';
	foreach ($openids_arr['data']['openid'] as $openid) {
		$openids .= '"' . $openid . '",';
	}
	$openids = rtrim( $openids, ',');
	$air = file_get_contents('AirQualityChina.txt');
	$weather = weather();
	$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=' . $access_token;
	$msg = 'Hi   ' . "   早上好 !\n\n" . $air . "\n\n" . $weather . "\n更多详情请参考自己手机app";
	$data = create_mass( $openids, $msg);
	$data = $res->http_request_POST( $url, $data);
	var_dump( $data);

	/*发送群发*/
	function create_mass( $openids, $msg ) {
		$data = '{
				"touser" : [
							' . $openids . '
						],
				"msgtype" : "text",
				"text":
						{
							"content":"'.$msg.'"
						}
			}';
		return $data;
	}
 ?>