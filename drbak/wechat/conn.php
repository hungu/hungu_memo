<?php 
	@$conn = mysql_connect('127.0.0.1','root','password') or die('数据库连接错误'.mysql_error());
	@mysql_select_db('drbak', $conn) or die('数据库连接错误'.mysql_error());
	@mysql_query('set names utf8', $conn) or die('数据库连接错误'.mysql_error());
	date_default_timezone_set($timezone); //北京时间
