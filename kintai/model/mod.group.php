<?php
// *** create new group ***
function insertGroup($array, $mysqli)
{
	$query = "INSERT INTO groups(group_name, group_intime, group_outtime, create_date, delete_flag) ";
	$query .= "VALUES('" . $array['gname'] . "', '" . $array['gin'] . "', '" . $array['gout'] . "', NOW(), 0)";

	$stmt = $mysqli->prepare($query);
	if ($stmt->execute()) {
		return true;
	}

	return false;
}

// *** create new group ***
function updateGroup($array, $mysqli)
{
	$query = "UPDATE groups SET group_name='".$array['gname']."', group_intime='".$array['gin']."', group_outtime='".$array['gout']."'";
	$query .= " WHERE group_id=" . $array['gid'];

	$stmt = $mysqli->prepare($query);
	if ($stmt->execute()) {
		return true;
	}

	return false;
}

// *** update group ***
function getGroupList($mysqli) {
	$query = "SELECT * FROM groups";

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

// *** update group ***
function getGroupTime($array, $mysqli) {
	$query = "SELECT * FROM groups g ";
	$query .= "LEFT JOIN user u ON g.group_id = u.group_id ";
	$query .= "WHERE u.user_id = " . $array;

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