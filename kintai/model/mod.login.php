<?php
function logout()
{
	$_SESSION = array();
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	session_destroy();
	return true;
}

function login($mysqli, $username)
{
	$query = "SELECT user_id, user_eid, user_name, user_password, user_salt, user_role, branch_id FROM user WHERE user_eid = '$username'";
	if ($stmt = $mysqli->query($query)) {
		return $stmt;
	}
}

function checkSession($session) {
	if(!isset($session) || $session == "" || $session == NULL) {
		header("location: " . ROOT . "index.php");
		exit;
	}
}

function checkLogin($role, $session)
{
	if ($role == "user") {
		// if user role is admin, redirect to admin's home page
		if ($session == 0) {
			header("location: " . ROOT . "deny.html");
			exit;
		}
	}

	if ($role == "admin") {
		// if user role is user, redirect to user's home page
		if ($session == 1) {
			header("location: " . ROOT . "deny.html");
			exit;
		}
	}
}