
<?php
error_reporting(0);
define("TOKEN", "hungu");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}
class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    /*回复判断*/
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty( $postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $type = trim( $postObj->MsgType);
            switch ( $type) {
                case 'event':
                    $result = $this->dealEven($postObj);
                    break;
                case 'voice':
                    $Content = $this->removeMark(trim( $postObj->Recognition));
                    $result = $this->dealText( $postObj, $Content);
                    include('msg_push.php');
                    msg_push($postObj->FromUserName, $Content, '3');
                    break;
                case 'text':
                    $Content = $this->removeMark(trim( $postObj->Content));
                    $result = $this->dealText($postObj, $Content);
                    include('msg_push.php');
                    msg_push($postObj->FromUserName, $Content, '1');
                    break;
                default:
                    $result = "";
                    break;
            }
            echo $result;
        }else{
            echo "";
            exit;
        }
    }
    /*处理关注事件*/
     private function dealEven($object) {
        $openid = $object->FromUserName;
        switch ($object->Event) {
            case 'subscribe':
                $content = "欢迎关注\n";
                include('wechat_deal.php');
                $pass = register_deal($openid);
                $name = get_name($openid);
                $nickname = get_user_nickname($openid);
                if(isset($pass) & isset($name) & isset($nickname)) {
                    $nickname = 'Hi, ' . $nickname . "\n";
                    $user = '您的用户名: ' . $name . "\n";
                    $pass = '密码: ' . $pass;
                    $content = $nickname . $content . $user . $pass;
                }
                $result = $this->replyText( $object, $content);
                /*include('msg_push.php');
                msg_push($openid, '&', '4');*/
                break;
            case 'CLICK':
                switch ($object->EventKey) {
                    case '天气':
                        $content = $this->weather();
                        $result = $this->replyText( $object, $content);
                        include('msg_push.php');
                		msg_push($openid, '天气', '2');
                        break;
                    case '备忘':
                        include('msg_push.php');
                        include('memo.php');
                        $content = memo($openid);
                        $result = $this->replyText( $object, $content);
                		msg_push($openid, '备忘', '2');
                        break;
                    case '空气':
                        $city = "北京";
                        $content = $this->getAirQualityChina( $city);
                        $result = $this->replayNews( $object, $content);
                        include('msg_push.php');
                		msg_push($openid, '空气', '2');
                        break;
                    default:
                        $content = "";
                        break;
                }
                break;
            default:
                $content = "";
                break;
        }
        return $result;
    }
    /*过滤文本的标点符号*/
    private function removeMark($text){
        $text=urlencode($text); 
        $text=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|
                                \-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|
                                %A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|
                                %A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|
                                %EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|
                                %E2%80%98|%E2%80%99|%EF%BD%9E|%EF%BC%8E|%EF%BC%88)+/",' ',$text); 
        $text=urldecode($text); 
        return trim($text); 
    }
    /*处理文本*/
    private function dealText($object, $content){
        $result = "";
        $reco = "";
        switch ( $content) {
            case '空气':
                $city = "北京";
                $content = $this->getAirQualityChina( $city);
                $result = $this->replayNews( $object, $content);
                break;
            case '天气':
                $content = $this->weather();
                $result = $this->replyText( $object, $content);
                break;
            case 'mima':
                $openid = $object->FromUserName;
                include('wechat_deal.php');
                $user = '您的用户名: ' . get_name($openid) . "\n";
                $pass = '密码: ' . register_deal($openid);
                $result = $this->replyText( $object, $user . $pass);
                break;
            default:
                $id_msg = $this->id_card( $content );
                if( $id_msg ) {
                    $result = $this->replyText( $object, $id_msg);
                    break;
                }
                $phone_num = $this->phone_num( $content );
                if( $phone_num ) {
                    $result = $this->replyText( $object, $phone_num );
                    break;
                }
                $reco = $content."\n";
                $content = $this->translateYoudao( trim( $content));
                $content = $reco.$content;
                $result = $this->replyText( $object, $content);
                break;
        }
        return $result;
    }
    /*手机号码归属地*/
    private function phone_num( $phone_num ) {
        $ch = curl_init();
        $url = 'http://apis.baidu.com/apistore/mobilephoneservice/mobilephone?tel=' . $phone_num;
        $header = array(
            'apikey: e37a57971cd71b49a418b197ab43a40a',
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch , CURLOPT_URL , $url);
        $data = curl_exec($ch);
        $data = json_decode( $data , true );
        $retData = $data['retData'];
        if(( $data['errMsg'] != 'success' ) || ( $retData['province'] == '' )) {
            return false;
        }
        $result = '手机号码: ' . $retData['telString']  . "\n" .
            '归属地: ' . $retData['province'] . "\n" .
            '运营商: ' . $retData['carrier'];
        return $result;
    }
    /*id_card*/
    private function id_card( $id ) {
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
          if( $data['retMsg'] != 'success') {
            return false;
          }
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
    /*空气质量*/
    private function getAirQualityChina( $city){
        $aqiArray = array();
        if($result = file_get_contents('AirQualityChina.txt')) {
            $aqiArray[] = array("Title"=>"北京空气质量", "Description"=>$result, "PicUrl"=>"", "Url"=>"");
            return $aqiArray;
        }
        $aqiArray[] = array("Title"=>"服务器繁忙,请稍后再试!", "Description"=>$result, "PicUrl"=>"", "Url"=>"");
        return $aqiArray;
       
    }
    /*天气*/
    private function weather() {
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
    /*有道翻译*/
    private function translateYoudao( $word ) {
        if( $word == ""){
            return "请输入要翻译的内容";
        }
        $apihost = "http://fanyi.youdao.com/";
        $apimethod = "openapi.do?";
        $apiparams = array( 'keyfrom'=>"zuixinan", 'key'=>"1753331900",
                             'type'=>"data", 'doctype'=>"json", 'version'=>"1.1", 'q'=>$word);
        $apicallurl = $apihost.$apimethod.http_build_query( $apiparams);

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $apicallurl);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec( $ch);

        $youdao = json_decode( $output, true);
        $result = "";
        switch ( $youdao['errorCode']){
            case 0:
                if(isset( $youdao['basic'])){
                    $result .= $youdao['basic']['phonetic']."\n";
                    foreach ( $youdao['basic']['explains'] as $value) {
                        $result .= $value."\n";
                    }
                }else{
                    $result = "未找到";
                }
                break;
        }
        return trim( $result);
    }
    /*文字回复模版*/
    private function replyText( $object, $content){
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
        $result = sprintf( $textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
    /*图文回复模版*/
    private function replayNews( $object, $content){
        if(!is_array( $content)){
            return;
        }
        $itmeTP1 = "<item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                    </item>";
        $con_str = "";
        foreach ( $content as $value) {
            $con_str .= sprintf( $itmeTP1, $value['Title'], $value['Description'], $value['PicUrl'], $value['Url']);
        }
        $newsTP1 = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <Content><![CDATA[]]></Content>
                        <ArticleCount>%s</ArticleCount>
                        <Articles>$con_str</Articles>
                        </xml>";
        $result = sprintf( $newsTP1, $object->FromUserName, $object->ToUserName,  time(), count( $con_str));
        return $result;
    }
}
?>