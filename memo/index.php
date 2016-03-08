<?php 
	session_start();
	error_reporting(0);
	if( isset( $_SESSION['memo_name'] ) && isset( $_SESSION['memo_id'] )) {
		header('Location: memo.php');
	} else {
		header('Location: login.html');
	}
 ?>