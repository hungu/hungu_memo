<?php 
	/*GET(http_request_GET)和POST(http_request_POST)
	  通过curl函数获取数据*/
	class http_request {
		public function http_request_GET($url) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url);
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec( $ch);
			if(curl_errno($ch)) {
				return FALSE;
			}
			curl_close($ch);
			return $data;
		}
		public function http_request_POST($url, $data = null ) {
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
			if(curl_errno($ch)) {
				return FALSE;
			}
			curl_close( $ch);
			return $output;
		}
	}