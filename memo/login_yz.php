<?php 
	session_start();
	error_reporting(0);
	if( isset( $_POST['name'] ) && isset( $_POST['pass'] )) {
		include('./wechat/conn.php');
		$name = mysql_escape_string( $_POST['name'] );
		$pass = md5( $_POST['pass'] );
		$sql = 'SELECT id,passwd FROM user WHERE name="' . $name . '"';
		$rs = mysql_query( $sql );
		$row = mysql_fetch_assoc( $rs );
		if( $row['passwd'] === $pass && isset( $row['id'] )) {
			$_SESSION['memo_name'] = $name;
			$_SESSION['memo_id'] = $row['id'];
			echo 'yes';
		} else {
			echo 'no';
		}
	} else {
		echo 'no';
	}
 ?>