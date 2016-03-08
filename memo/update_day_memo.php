<?php

	/*考虑增加一个表单存储今日memo提升性能,但数据量很小,而代码复杂性高,故放弃*/
	function update_day_memo($starttime = '', $endtime = '', $title = '', $allday = 1, $type = 0) {
		$do = 0;
		$today = strtotime(date('Y-m-d', time()));
		if(strtotime(date('Y-m-d', $starttime)) == $today && (strtotime(date('Y-m-d', $endtime)) < $today || strtotime(date('Y-m-d', $endtime)) > $today)) {
			if($allday) {
			} else {
				$endtime = $today + 86340;
				$do = 1;
			}
		} else if(strtotime(date('Y-m-d', $rw['starttime'])) == $today && strtotime(date('Y-m-d', $rw['endtime'])) == $today) {
			if($rw['allday']) {
			} else {
				$do = 1;
			}
		}
		if(!$do) {
			return;
		}
		$sql = 'SELECT openid FROM user WHERE id =' . $_SESSION['memo_id'];
		$rs = mysql_query($sql);
		$row = mysql_fetch_assoc($rs);
		$openid = $row['openid'];
		switch ($type) {
			case 0:
				mysql_query("insert into `day_memo` (`title`,`starttime`,`endtime`,`openid`) values ('$title', '$starttime', '$endtime', '$openid')");
				break;
			
			default:
				# code...
				break;
		}
	}
 ?>