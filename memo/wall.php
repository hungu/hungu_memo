<?php 
	session_start();
	error_reporting(0);
	if(!isset( $_SESSION['memo_name'] )) {
		$_SESSION['msg'] = '请先登陆!';
		header('Location: login.html');
		exit;
	}