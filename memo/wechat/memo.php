<?php 
	require_once('conn.php');
	/*function __autoload($class) {
		include($class.'.php');
	}*/
	function memo($openid = '') {
		$memo = '';
		$sql = 'SELECT id FROM user WHERE openid="' . $openid . '"';
		$rs = mysql_query($sql);
		$row = mysql_fetch_assoc($rs);
		$sql = 'SELECT * FROM calendar WHERE uid=' . $row['id'];
		$rs = mysql_query($sql);
		if(!mysql_num_rows($rs)) {
			$memo = '今日无备忘';
		} else {
			while ($rw = mysql_fetch_assoc($rs)) {
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
		return $memo;
	}
	
	