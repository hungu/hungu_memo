<?php 
	@$conn = mysql_connect('127.0.0.1','root','password') or die('���ݿ����Ӵ���'.mysql_error());
	@mysql_select_db('drbak', $conn) or die('���ݿ����Ӵ���'.mysql_error());
	@mysql_query('set names utf8', $conn) or die('���ݿ����Ӵ���'.mysql_error());
	date_default_timezone_set($timezone); //����ʱ��