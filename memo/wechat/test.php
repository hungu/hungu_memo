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
    var_dump($cityAir);
 ?>