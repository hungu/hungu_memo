<?php 
	error_reporting(0);
	include('conn.php');
	function __autoload($class) {
		include($class.'.php');
	}

	$today = strtotime(date('Y-m-d', time()));
	if(time() - $today >= 86340) {				//判断是否是凌成0:00
		$today += 86400;
	}
	$time_left = time() + 10;
	$time_right = $time_left + 600;

	$acc = new access_token( '4297f44b13955235245b2497399d7a93.xml' );
	$access_token = $acc;
	$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
	$res = new http_request();

	$sql = 'SELECT * FROM user';
	$rs = mysql_query($sql);
	while($row = mysql_fetch_assoc($rs)) {
		$memo = array();
		$sql = 'SELECT * FROM calendar WHERE uid=' . $row['id'];
		$ros = mysql_query($sql);
		if(!mysql_num_rows($ros)) {
		} else {
			while ($rw = mysql_fetch_assoc($ros)) {
				if(strtotime(date('Y-m-d', $rw['starttime'])) == $today && (strtotime(date('Y-m-d', $rw['endtime'])) < $today || strtotime(date('Y-m-d', $rw['endtime'])) > $today)) {
					if($rw['allday']) {
					} else {
						if(($rw['starttime'] > $time_left) && ($rw['starttime'] <= $time_right)) {
							$memo['starttime'][] = $rw['starttime'];
							$memo['endtime'][] = $today + 86340;
							$memo['title'][] = $rw['title'];
						}
					}
				} else if(strtotime(date('Y-m-d', $rw['starttime'])) == $today && strtotime(date('Y-m-d', $rw['endtime'])) == $today) {
					if($rw['allday']) {
					} else {
						if(($rw['starttime'] > $time_left) && ($rw['starttime'] <= $time_right)) {
							$memo['starttime'][] = $rw['starttime'];
							$memo['endtime'][] = $rw['endtime'];
							$memo['title'][] = $rw['title'];
						}
					}
				}
			}
			print_r($memo);
			if(!isset($memo['starttime'][0])) {
			} else {
				foreach ($memo['starttime'] as $k => $v) {
					$template = array(
							'touser'=>$row['openid'],
							'template_id'=>'MbCxJOz5mMwINU5hpBjm-Pjv28ug0F8k0bEAAK5iqI8',
							'url'=>'http://memo.zuixinan.top',
							'topcolor'=>'#7B68EE',
							'data'=>array(
											'first'=>array('value'=>urlencode($memo['title'][$k] . "\\n")),
											'word1'=>array('value'=>urlencode(date('H:i', $v))),
											'word2'=>array('value'=>urlencode(date('H:i', $memo['endtime'][$k]))),
											'word3'=>array('value'=>urlencode($memo['title'][$k])),
											'remark'=>array('value'=>urlencode('\\n不要忘记哦!')),
							)
					);
					$res->http_request_POST( $url, urldecode(json_encode($template)));
				}
			}
		}
	}
 ?>