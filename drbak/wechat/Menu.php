<?php
	$access_token ="d0Y5ZzUN2T_E07Df1ljbFLIl5YkMgmH-kxAFKjRRNGU2VdyF52gMSHN5Eu6fhlbDSVVaKpcr8cJflDTdGdy_vMb7oqB-aHl4a-nMOHw6sV8DGXjABAXAR";
	$menu = '{
		"button":[
			{
				"name":"天气状况",
				"type":"click",
				"key":"天气"
			},
			{
				"name":"空气质量",
				"type":"click",
				"key":"空气"
			},
			{
				"name":"今日备忘",
				"type":"click",
				"key":"备忘"
			}
		]
	}';
	$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
	$result = https_request( $url, $menu);
	var_dump( $result);
	
	function https_request( $url, $data = NULL){
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if( !empty( $data)){
			curl_setopt( $ch, CURLOPT_POST, 1);
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec( $ch);
		curl_close( $ch);
		return $output;
	}
?>