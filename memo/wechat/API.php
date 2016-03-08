<?php 
	function air_quality( $city ) {
		$url = "Http://www.pm25.in/api/querys/aqi_details.json?avg=true&stations=no&city=".urldecode( $city)."&token=5j1znBVAsnSf5xQyNQyq";
		$send_curl = new http_request();
		$data = $send_curl->http_request_GET( $url );
		if( ! $data ) {
			return false;
		}
		$city_air = json_decode( $data, true);
		if(isset( $city_air['error'])){
        return $city_air['error'];
    }
    $city_air = $city_air[0];
		$result = "空气质量指数（AQI）：".$city_air['aqi']."\n".
              "空气质量等级: ".$city_air['quality']."\n".
              "细颗粒物(PM2.5): ".$city_air['pm2_5']."\n".
              "可吸入颗粒物(PM10): ".$city_air['pm10']."\n".
              "一氧化碳: ".$city_air['co']."\n".
              "二氧化氮: ".$city_air['no2']."\n".
              "三氧化硫: ".$city_air['so2']."\n".
              "臭氧: ".$city_air['o3']."\n".
              "更新时间: ".preg_replace("/([a-zA-Z])/i", "  ", $city_air['time_point']);
    return $result;
	}
  function weather() {
    $url = 'http://apis.baidu.com/heweather/weather/free?city=beijing';
    $apikey = $header = array(
          'apikey: e37a57971cd71b49a418b197ab43a40a',
      );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $apikey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch , CURLOPT_URL , $url);
    $data = curl_exec($ch);
    $data = json_decode( $data , true );
    $weather_today = $data['HeWeather data service 3.0'][0]['daily_forecast'][0];
    $result_today = "\n" . '---今日天气---' . "\n" . "白天：" . $weather_today['cond']['txt_d']."\n".
                "夜间：" . $weather_today['cond']['txt_n']."\n".
               "最高温度: ".$weather_today['tmp']['max']."度\n".
               "最低温度: ".$weather_today['tmp']['min']."度\n".'----------' . "\n\n";
    $weather_now = $data['HeWeather data service 3.0'][0]['now'];
    $result_now = '--当前天气--' . "\n" . "天气状况：".$weather_now['cond']['txt']."\n".
              "温度: ".$weather_now['tmp']."度\n".
              "相对湿度（%）: ".$weather_now['hum']."\n";
    return $result_today . $result_now;
  }
  function id_card( $id ) {
    $url = 'http://apis.baidu.com/apistore/idservice/id?id=' . $id;
    $header = array(
          'apikey: e37a57971cd71b49a418b197ab43a40a',
      );
    $ch = curl_init();
    curl_setopt( $ch , CURLOPT_HTTPHEADER  , $header );
      curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
      curl_setopt( $ch , CURLOPT_URL , $url );
      $data = curl_exec( $ch );
      $data = json_decode( $data , true );
      $retData = $data['retData'];
      $sex = '';
      switch ( $retData['sex'] ) {
        case 'M':
          $sex = '男';
          break;
        case 'F':
          $sex = '女';
          break;
        default:
          $sex = '未知';
          break;
      }
      $result = '性别: ' . $sex  . "\n" .
          '出生日期: ' . $retData['birthday'] . "\n" .
          '身份证归属地: ' . $retData['address'];
      return $result;
  }
 ?>