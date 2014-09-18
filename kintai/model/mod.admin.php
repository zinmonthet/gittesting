<?php
function getUserList($mysqli, $showadminflag = false)
{
	$data = Array();
	if ($showadminflag) {
		$query = "SELECT u.user_id as user_id, u.user_name as user_name, u.user_eid as user_eid, u.email as email, u.department as department, u.user_role as user_role, u.group_id as group_id, u.delete_flag as delete_flag, g.group_name as group_name, g.group_intime as group_intime, g.group_outtime as group_outtime FROM user u ";
		$query .= "LEFT JOIN groups g ON u.group_id = g.group_id ";
		$query .= "ORDER BY user_role ASC";
	} else {
		$query = "SELECT u.user_id as user_id, u.user_name as user_name, u.user_eid as user_eid, u.email as email, u.department as department, u.user_role as user_role, u.group_id as group_id, u.delete_flag as delete_flag, g.group_name as group_name, g.group_intime as group_intime, g.group_outtime as group_outtime FROM user u ";
		$query .= "LEFT JOIN groups g ON u.group_id = g.group_id ";
		$query .= "WHERE user_role = 1 AND u.delete_flag = 0";
	}

	if ($stmt = $mysqli->query($query)) {
		if ($stmt->num_rows > 0) {
			while ($result = $stmt->fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function insert_data($array, $mysqli)
{
	$query = "INSERT INTO user(user_name,user_eid,user_password,user_salt,default_pw,email,department,user_role,group_id,branch_id,create_date)";
	$query .= " VALUES('" . $array['name'] . "', '" . $array['userid'] . "', '" . $array['new_pass'] . "', '" . $array['salt'] . "', '".$array['default_pw']."', ";
	$query .= "'" . $array['email'] . "',";
	$query .= "'" . $array['dep_name'] . "', '" . $array['role'] . "', " . $array['group'] .",".$array['branch']. ", NOW() ";
	$query .= ")";

	if ($mysqli->query($query)) {
		return true;
	}

	return false;
}

function getdetail($edit_id, $mysqli)
{
	$query = "SELECT * FROM user WHERE user_id=$edit_id";

	if ($stmt = $mysqli->query($query)) {
		if ($stmt->num_rows > 0) {
			while ($result = $stmt->fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function delete_user($delete_id, $mysqli)
{
	$query = "UPDATE user SET delete_flag=1 WHERE user_id =" . $delete_id;
	if ($mysqli->query($query)) {
		return true;
	}

	return false;
}

function update_user($editarray, $mysqli)
{
	$query = "UPDATE `user` SET user_name = '" . $editarray['username'] . "', department='" . $editarray['deptname'] . "', user_role='" . $editarray['role'] . "'";
	$query .= ", email='" . $editarray['email'] . "'";
	$query .= " WHERE user_id = " . $editarray['u_id'];

	if ($mysqli->query($query)) {
		return true;
	}

	return false;
}

function getprofile($userid, $mysqli)
{
	$query = "SELECT * FROM user WHERE user_eid='" . $userid . "'";
	if ($stmt = $mysqli->query($query)) {
		if ($stmt->num_rows > 0) {
			while ($result = $stmt->fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function getUserProfileById($userid, $mysqli)
{
	$query = "SELECT * FROM user WHERE user_id='" . $userid . "' LIMIT 0,1";
	if ($stmt = $mysqli->query($query)) {
		if ($stmt->num_rows > 0) {
			while ($result = $stmt->fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function updateprofile($proarray, $mysqli)
{
	$id = $_SESSION['sess_user_eid'];
	if ($proarray['password'] == 0) {
		$query = "UPDATE `user` SET user_name = '" . $proarray['username'] . "', department='" . $proarray['deptname'] . "', email='" . $proarray['email'] . "'";
		$query .= " WHERE user_eid ='" . $id . "'";
		if ($mysqli->query($query)) {
			return true;
		}

		return false;
	} else {
		$query = "UPDATE `user` SET user_name = '" . $proarray['username'] . "', department='" . $proarray['deptname'] . "', user_password='" . $proarray['password'] . "'";
		$query .= ", user_salt='" . $proarray['salt'] .  "', default_pw='".$proarray['default_pw']."', "." email='" . $proarray['email'] . "'";
		$query .= " WHERE user_eid ='" . $id . "'";

		if ($mysqli->query($query)) {
			return true;
		}
		return false;
	}
}

function getusermail($usermail, $mysqli)
{
	$query = "SELECT * FROM user WHERE email='" . $usermail . "'";

	if ($stmt = $mysqli->query($query)) {
		if ($stmt->num_rows > 0) {
			while ($result = $stmt->fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}


function update_psw($newpsw, $salt, $hemail, $mysqli)
{
	$query = "UPDATE `user` SET user_password = '" . $newpsw . "', user_salt='" . $salt . "'";
	$query .= " WHERE email ='" . $hemail . "'";
	echo $query;
	if ($mysqli->query($query)) {
		return true;
	}

	return false;
}