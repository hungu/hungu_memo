<?php 
	require_once('conn.php');
	function __autoload($class) {
		include($class.'.php');
	}
	function register_deal($openid) {
		$sql = 'SELECT 1 FROM user WHERE openid = "' . $openid .'"';
		$rs = mysql_query($sql);
		$num = mysql_num_rows($rs);
		if($num === 0) {
			$pass = register($openid);
			return $pass;
		} else {
			$pass = rand(100000, 999999);
			$sql = 'UPDATE  `drccr`.`user` 
						SET  
					`passwd` =  "'.md5($pass).'" WHERE  `user`.`openid` = "'. $openid .'"';
			$rs = mysql_query($sql);
			if($rs) {
				return $pass;
			}
			return false;
		}
	}
	function register($openid) {
		$pass = rand(100000, 999999);
		$nickname = get_user_nickname($openid);
		$nick = $nickname;
		/*确保用户名唯一性*/
		for($i = 0; $i < 10 ; $i++) {
			$sql = 'SELECT 1 FROM user WHERE name = ' . $nickname;
			$rs = mysql_query($sql);
			$num = mysql_num_rows($rs);
			if($num == 0) {
				break;
			} else {
				$nickname .= $i;
			}
			if($i == 9) {
				$nickname = $openid;
				break;
			}
		}
		$sql = 'INSERT INTO `user` (`name`,`passwd`,`nickname`,`openid`) 
					VALUES 
				("'.$nickname.'", "'.md5($pass).'", "'.$nick.'", "'.$openid.'")';
		if(mysql_query($sql)) {
			return $pass;
		} else {
			return false;
		}
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
	function get_name($openid) {
		$sql = 'SELECT name FROM user WHERE openid = "' . $openid .'"';
		$rs = mysql_query($sql);
		if($rs) {
			$row = mysql_fetch_assoc($rs);
			return $row['name'];
		}
		return false;

	}
 ?>