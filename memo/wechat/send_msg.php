<?php 
	class send_msg {
		private $openid;
		public function __construct( $openid ) {
			if( ! is_null( $openid )) {
				$this->openid = $openid;
			}
		}
		public function send( $msg ) {
			$acc = new access_token('4297f44b13955235245b2497399d7a93.xml');
			$access_token = $acc;//获取access_token
			$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
			$send_curl = new http_request(); //发送类
			$data = $this->create_data( $msg );
			$a = $send_curl->http_request_POST($url,$data);
		}
		private function create_data( $msg ) {
			$data = '{
					"touser" : "'.$this->openid.'",
					"msgtype" : "text",
					"text":
							{
								"content":"'.$msg.'"
							}
				}';
			return $data;
		}
	}
 ?>