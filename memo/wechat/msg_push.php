<?php 
	require_once('access_token.php');
	require_once('http_request.php');
	require_once('conn.php');
	function msg_push($openid = '', $msg = '', $node = '') {
		if($openid == '' || $msg == '' || $node == '' ) {
			return;
		} else {
			if($msg == '&') {
				$msg == '';
			}
			$node_arr = array(
								'1'=>'输入',
								'2'=>'点击',
								'3'=>'语音',
								'4'=>'关注',
								'5'=>'取消关注',

						);
			$nickname = get_user_nickname_save($openid);
			$msg = "$node_arr[$node] : " . $msg;
			$sql = 'INSERT INTO msg_save (nickname, msg, time) VALUES ("'. $nickname .'", "'. $msg .'", '. time() .')';
			mysql_query($sql);
			return;
		}
	}
	function get_user_nickname_save($openid) {
		$acc = new access_token('4297f44b13955235245b2497399d7a93.xml');
		$access_token = $acc;
		$get_nickname = new http_request();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
		$nickname_json = $get_nickname->http_request_GET($url);
		$nickname_array = json_decode($nickname_json, true);
		return $nickname_array['nickname'];
	}
 ?>