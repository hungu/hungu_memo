<?php 
	include('conn.php');
	function __autoload($class) {
		include($class.'.php');
	}
	$sql = 'SELECT * FROM user';
	$rs = mysql_query($sql);
	while($row = mysql_fetch_assoc($rs)) {
		$memo = '';
		$sql = 'SELECT * FROM calendar WHERE uid=' . $row['id'];
		$ros = mysql_query($sql);
		if(!mysql_num_rows($ros)) {
			$memo = '今日无备忘';
		} else {
			while ($rw = mysql_fetch_assoc($ros)) {
				$today = strtotime(date('Y-m-d', time()));
				if(strtotime(date('Y-m-d', $rw['starttime'])) == $today && (strtotime(date('Y-m-d', $rw['endtime'])) < $today || strtotime(date('Y-m-d', $rw['endtime'])) > $today)) {
					if($rw['allday']) {
						$memo .= '00:00 ~ 23:59 : ' . $rw['title'] . "\n";
					} else {
						$memo .= date('H:i', $rw['starttime']) . ' ~ 23:59 : ' . $rw['title'] . "\n";
					}
				} else if(strtotime(date('Y-m-d', $rw['starttime'])) < $today && strtotime(date('Y-m-d', $rw['endtime'])) >= $today) {
					if(strtotime(date('Y-m-d', $rw['endtime'])) > $today) {
						$memo .= '00:00 ~ 23:59 : ' . $rw['title'] . "\n";
					} else {
						if(!$rw['allday']) {
							$memo .= '00:00 ~ ' . date('H:i', $rw['endtime']) . ' : ' . $rw['title'] . "\n";
						} else {
							$memo .= '00:00 ~ 23:59 : ' . $rw['title'] . "\n";
						}
					}
				} else if(strtotime(date('Y-m-d', $rw['starttime'])) == $today && strtotime(date('Y-m-d', $rw['endtime'])) == $today) {
					if($rw['allday']) {
						$memo .= '00:00 ~ 23:59 : ' . $rw['title'] . "\n";
					} else {
						$memo .= date('H:i', $rw['starttime']) . ' ~ ' . date('H:i', $rw['endtime']) . ' : ' . $rw['title'] . "\n";
					}
				}
			}
			if($memo == '') {
				$memo = '今日无备忘';
			} else {
				$memo ='今日备忘' . "\n\n" . $memo;
			}
		}
		$send = new send_msg($row['openid']);
		$send->send($memo);
	}
 ?>