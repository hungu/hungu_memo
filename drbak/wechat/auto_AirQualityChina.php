<?php 
	$str = '';
	$city = '北京';
    $url = "Http://www.pm25.in/api/querys/aqi_details.json?avg=true&stations=no&city=".urldecode( $city)."&token=5j1znBVAsnSf5xQyNQyq";
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt( $ch, CURLOPT_URL, $url);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec( $ch);
    curl_close( $ch);
    
    $cityAir = json_decode( $output, true);
    if(isset( $cityAir['error'])) {
        $str = '';
    } else if($cityAir[0]['aqi'] == 0) {
		$str = '';
	}else {
        $result = "空气质量指数（AQI）：".$cityAir[0]['aqi']."\n".
                  "空气质量等级: ".$cityAir[0]['quality']."\n".
                  "细颗粒物(PM2.5): ".$cityAir[0]['pm2_5']."\n".
                  "可吸入颗粒物(PM10): ".$cityAir[0]['pm10']."\n".
                  "一氧化碳: ".$cityAir[0]['co']."\n".
                  "二氧化氮: ".$cityAir[0]['no2']."\n".
                  "三氧化硫: ".$cityAir[0]['so2']."\n".
                  "臭氧: ".$cityAir[0]['o3']."\n".
                  "更新时间: ".preg_replace("/([a-zA-Z])/i", "  ", $cityAir[0]['time_point']);
    	$str = $result;
    }
    $file = 'AirQualityChina.txt';
    if($str != '') {
    	$fp = fopen($file, 'w');
    	if(flock($fp, LOCK_EX)) {
    		fwrite($fp, $str);
    		flock($fp, LOCK_UN);
    	}
    	fclose($fp);
    }
 ?>