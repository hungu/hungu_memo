<?php
	
    /*根据构造时传入的token判断是否合法调用
       如果合法,读取保存 access_token 的xml文件
       判断access_token 是否过期
       过期从新获取,并更新xml文档*/

   	
	class access_token {
		private $xml = array();
		private $url ='';
		/*构造函数*/
		
		function __construct( $url ) {
			if( file_exists( $url)) {
				$this->url = $url;
				$this->read_xml();
				$this->judge_access_token_act();
			}
		}

		/*读取xml文件*/
		private function read_xml() {
        	$xml = simplexml_load_file($this->url);
        	foreach ($xml as $k => $v) {
        		$this->xml[$k] = trim((string)$v);
        	}
		}

		/*更新xml文件*/
		private function write_xml() {
			$xml = simplexml_load_file($this->url);
			$xml->time = time();
			$xml->access_token = $this->xml['access_token'];
			$file = fopen($this->url,'w');
			fwrite($file,$xml->asXML());
			fclose($file);
		}

		/*获取access_token*/
		private function set_access_token() {
			$appid = $this->xml['appid'];
			$appsecret = $this->xml['appsecret'];
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
			
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url);
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec( $ch);
			curl_close( $ch);
			$jsoninfo = json_decode( $output, true);
			$this->xml['access_token'] = $jsoninfo["access_token"];
		}

		/*判断access_token是否超时*/
		private function judge_access_token_act(){
			$time = time() - (int)$this->xml['time'];
			if($time >= 1800){
				$this->set_access_token();
				$this->write_xml();
			}else{
				$this->access_token = $this->xml['access_token'];
			}
		}

		/*获取access_token*/
		function __toString() {
			return $this->xml['access_token'];
		}
	}