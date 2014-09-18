<?php
function insertUsersBatch($data, $mysqli)
{
	for ($i = 0; $i < count($data); $i++) {
		$query = "INSERT INTO user(user_name, user_eid, user_password, user_salt , email , department , user_role, group_id, create_date,delete_flag)";
		$query .= " VALUES('" . $data[$i]['user_name'] . "', '" . $data[$i]['user_eid'] . "', '" . $data[$i]['user_password'] . "', '" . $data[$i]['user_salt'] . "', '" . $data[$i]['email'] . "', '" . $data[$i]['department'] . "', " . $data[$i]['user_role'] .", ". $data[$i]['group_id'] . ",  NOW(), 0)";
        $stmt = $mysqli->prepare($query);
		if (!$stmt->execute()) {
			return false;
		}
	}
	return true;
}