<?php
//session_start();
include_once ("ini.setting.php");
include_once ("mod.select.php");

if (isset($_POST['submit'])) {
	$name = $_POST['username'];
	$psw = $_POST['psw'];
	$password = hash("sha256", $psw);
	//echo $password;
	$result = getpsw($name);
	$row = mysql_fetch_array($result);

	define("MAX_LENGTH", 6);
	$intermediateSalt = md5(uniqid(rand(), true));
	$salt = substr($intermediateSalt, 0, MAX_LENGTH);
	$psw_salt = hash("sha256", $password . $row['user_salt']);
}
