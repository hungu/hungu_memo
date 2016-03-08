<?php
	$template = array(
					'touser' => "",
					'template_id' => "",
					'url' => "",
					'topcolor' => '',
					'data' => array(
								'first' => array('value' => urlencode(""))£¬'color' => "#743A3A",),
								'product' => array('value' => urlencode(""))£¬'color' => "#FF0000",),
							)
				);
	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
	$result = https_request($url, urldecode(json_encode($template)));
	var_dump($result);
	
	function https_request($url, $data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFTYEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
		curl_close($curl);
		return $output;
	}
?>