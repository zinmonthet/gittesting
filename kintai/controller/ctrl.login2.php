<?php
error_reporting(E_ALL);
ini_set( 'display_errors','1');

include_once("../lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.login.php");

if (isset($_GET['cmd']) && $_GET['cmd'] == "logout") {
	logout();
	header("location: " . ROOT . "index.php");
	exit;
} else {
	$usereid = $_POST['username'];
	$password = $_POST['password'];
	$password = hash("sha256", $password);
	$stmt = login($db, $usereid);

	if ($stmt->num_rows == 0) {
		header('Location: ' . ROOT . 'index.php?err=1');
		exit;
	} else {
		while ($result = $stmt->fetch_assoc()) {
			define("MAX_LENGTH", 6);
			$intermediateSalt = md5(uniqid(rand(), true));
			$salt = substr($intermediateSalt, 0, MAX_LENGTH);
			$hash = hash("sha256", $password . $result['user_salt']);
			if ($hash != $result['user_password']) // Incorrect password. So, redirect to login_form again.
			{
				header('Location: ' . ROOT . 'index.php?err=1');
				exit;
			} else {
				// Redirect to home page after successful login.
				session_regenerate_id();
				$_SESSION['sess_user_id'] = $result['user_id'];
				$_SESSION['sess_username'] = $result['user_name'];
				$_SESSION['sess_user_eid'] = $result['user_eid'];

				// user is administrator
				if ($result['user_role'] == 0) {
					$_SESSION['sess_user_role'] = $result['user_role'];
					$_SESSION['sess_user_eid'] = $result['user_eid'];
					header('Location: ' . ROOT . "admin/history.php");
					exit;
					// user is normal user
				} else if ($result['user_role'] == 1) {
					// ** add timezone
					$_SESSION['sess_user_intime'] = getWorkTime($result['user_eid'])['intime'];
					$_SESSION['sess_user_outtime'] = getWorkTime($result['user_eid'])['outtime'];
					$_SESSION['sess_user_now'] = getWorkTime($result['user_eid'])['now'];

					$_SESSION['sess_user_role'] = $result['user_role'];
					$_SESSION['sess_user_eid'] = $result['user_eid'];
					header('Location: ' . ROOT . 'home.php');
					exit;
				} else {
					header("location: " . ROOT . "error.html");
					exit;
				}
				session_write_close();
			}
		}
	}
}
