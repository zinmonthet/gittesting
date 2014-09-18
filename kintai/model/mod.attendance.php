<?php
function checkInCheck($userid, $array, $mysqli)
{
	$select = "SELECT * FROM attendance ";
	$select .= "WHERE attd_date = '" . $array["attd_date"] . "' AND attd_user_id = $userid";

	// prevent from check in twice
	if ($stmt = $mysqli->query($select)) {
		if ($stmt->num_rows > 0) {
			return true;
		}
	}
	return false;
}

/** get time of checkin **/
function getCheckInTime($userid, $todaydate, $mysqli)
{
	$todaydate = $todaydate[0] . "-" . $todaydate[1] . "-" . $todaydate[2];

	$query = "SELECT count(*) FROM attendance ";
	$query .= "WHERE attd_date = '" . $todaydate . "' AND attd_user_id = $userid LIMIT 0,1";

	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($counts);
	$stmt->fetch();

	if ($counts == 0 && $counts < 1) {
		return false;
	}

	$query = "SELECT * FROM attendance ";
	$query .= "WHERE attd_date = '" . $todaydate . "' AND attd_user_id = $userid LIMIT 0,1";

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

/** get time of checkout **/
function getCheckOutTime($userid, $todaydate, $mysqli)
{
	$todaydate = $todaydate[0] . "-" . $todaydate[1] . "-" . $todaydate[2];

	$query = "SELECT count(*) FROM attendance ";
	$query .= "WHERE attd_date='" . $todaydate . "' AND attd_user_id = $userid AND show_flag = 1 LIMIT 0,1";

	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($counts);
	$stmt->fetch();

	if ($counts == 0 && $counts < 1) {
		return false;
	}

	$query = "SELECT * FROM attendance ";
	$query .= "WHERE attd_date='" . $todaydate . "' AND attd_user_id = $userid AND show_flag = 1 LIMIT 0,1";

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

function checkIn($userid, $array, $mysqli)
{
	$query = "INSERT INTO attendance(attd_in_time, attd_user_id, attd_date, attd_comment_in, create_date, show_flag, delete_flag)";
	$query .= " VALUES('" . $array['attd_in_time'] . "', '" . $userid . "', '" . $array['attd_date'] . "', '" . $array['attd_comment'] . "', NOW(), 0, 0)";

	$stmt = $mysqli->prepare($query);
	if ($stmt->execute()) {
		return true;
	}
	return false;
}

function checkOutCheckIn($userid, $array, $mysqli)
{
	$query = "SELECT * FROM attendance ";
	$query .= "WHERE attd_date='" . $array['attd_date'] . "' AND attd_user_id=$userid";

	if ($stmt = $mysqli->query($query)) {
		if ($stmt->num_rows > 0) {
			return true;
		}
	}
	return false;
}

function checkOutCheckedIn($userid, $array, $mysqli)
{
	$query = "SELECT * FROM attendance ";
	$query .= "WHERE attd_date='" . $array['attd_date'] . "' AND attd_user_id=$userid AND show_flag = 1";

	if ($stmt = $mysqli->query($query)) {
		if ($stmt->num_rows > 0) {
			return true;
		}
	}
	return false;
}

function checkOut($userid, $array, $mysqli)
{
	$query = "UPDATE attendance SET attd_out_time='" . $array['attd_out_time'] . "', attd_comment_out='" . $array['attd_comment'] . "', show_flag=1";
	$query .= " WHERE attd_user_id = $userid AND attd_date='" . $array["attd_date"] . "' AND show_flag != 1";

	if ($stmt = $mysqli->query($query)) {
		return true;
	}
	return false;
}

function getLateCheckIn($userid, $intime, $currentmonthyear, $mysqli)
{
	$query = "SELECT count(*) as counts FROM attendance a ";
	$query .= "LEFT JOIN calendar c ON a.attd_date = c.calendar_date ";
	$query .= "WHERE attd_in_time > '" . $intime . "' AND attd_user_id = $userid ";
	$query .= "AND c.calendar_year = '$currentmonthyear[0]' AND c.calendar_month = '$currentmonthyear[1]'";

	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($counts);
	$stmt->fetch();

	return $counts;
}

function getEarlyCheckOut($userid, $outtime, $currentmonthyear, $mysqli)
{
	$query = "SELECT count(*) FROM attendance a ";
	$query .= "LEFT JOIN calendar c ON a.attd_date = c.calendar_date ";
	$query .= "WHERE attd_out_time < '" . $outtime . "' AND attd_user_id = $userid ";
	$query .= "AND c.calendar_year = '$currentmonthyear[0]' AND c.calendar_month = '$currentmonthyear[1]'";

	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($counts);
	$stmt->fetch();

	return $counts;
}

function getAbsent($userid, $currentmonthyear, $mysqli)
{
	$todaydate = explode("-", date("Y-n-j"));

	if ($currentmonthyear[1] != $todaydate[1]) // to check the month
	{
		$dayspermonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$days = $dayspermonth[$currentmonthyear[1] - 1];
		$currentmonthyear[2] = $days;
	} else {
		$currentmonthyear[2] = $todaydate[2];
	}
	/*.............. check is there userid in attendance table...............*/
	$getid = "SELECT attd_user_id from attendance join calendar on attd_date = calendar_date WHERE attd_user_id='" . $userid . "'";
	$getid .= "AND calendar_date BETWEEN '" . $currentmonthyear[0] . "-" . $currentmonthyear[1] . "-1' AND '" . $currentmonthyear[0] . "-" . $currentmonthyear[1] . "-" . $currentmonthyear[2] . "' AND calendar_status != 0 ";

	if ($stmt = $mysqli->query($getid)) {
		$counts = $stmt->num_rows;
	}
	/*.............if userid is in attendance table, calculte the absent date of that userid.............*/
	if ($counts > 0) {
		$query = "SELECT data.attd_date, c.calendar_date, c.calendar_id, c.calendar_status, c.calendar_year, c.calendar_month, calendar_events, calendar_day, data.attd_in_time, data.attd_out_time FROM
					(SELECT a.attd_date, c.calendar_id, c.calendar_status,
					 a.attd_in_time, a.attd_out_time
					 FROM attendance a
					 RIGHT JOIN calendar c ON a.attd_date = c.calendar_date
					 WHERE c.calendar_year = '" . $currentmonthyear[0] . "' AND c.calendar_month = '" . $currentmonthyear[1] . "'
					 AND a.attd_user_id = $userid
					) AS data RIGHT JOIN calendar c ON data.attd_date = c.calendar_date
			WHERE c.calendar_year = '" . $currentmonthyear[0] . "' AND c.calendar_month = '" . $currentmonthyear[1] . "'
			AND c.calendar_date BETWEEN '" . $currentmonthyear[0] . "-" . $currentmonthyear[1] . "-1' AND '" . $currentmonthyear[0] . "-" . $currentmonthyear[1] . "-" . $currentmonthyear[2] . "' AND c.calendar_status != 0
			AND attd_in_time IS NULL AND attd_out_time IS NULL";

		if ($stmt = $mysqli->query($query)) {
			$abcounts = $stmt->num_rows; // result the absent date of the userid in attendance table
		}

	} else //........... calculate the absent date of user id that is not in attendance table
	{
		$sql = "(select user_id from user where user_id='" . $userid . "')
				union
				(select calendar_date from calendar join attendance on attd_date != calendar_date where 
				calendar_date BETWEEN '" . $currentmonthyear[0] . "-" . $currentmonthyear[1] . "-1' AND '" . $currentmonthyear[0] . "-" . $currentmonthyear[1] . "-" . $currentmonthyear[2] . "')";

		if ($stmt = $mysqli->query($sql)) {
			$abcounts = $stmt->num_rows;
			$abcounts = $abcounts - 1; // count the absent date of user id that is not in attendance table
			//echo $abcounts;
		}
	}
	return $abcounts;
}

function getCurrentMonth($currentmonthyear, $userid, $mysqli)
{
	$query = "SELECT data.attd_date, c.calendar_date, c.calendar_id, c.calendar_status, c.calendar_year, c.calendar_month, calendar_events, calendar_day, data.attd_in_time, data.attd_out_time, data.attd_comment_in, data.attd_comment_out FROM
					(SELECT a.attd_date, 
					 c.calendar_id,
					 c.calendar_status,
					 a.attd_in_time,
					 a.attd_out_time,
					 a.attd_comment_in,
					 a.attd_comment_out
					 FROM attendance a
					 RIGHT JOIN calendar c ON a.attd_date = c.calendar_date
					 WHERE c.calendar_year = '" . $currentmonthyear[0] . "' AND c.calendar_month = '" . $currentmonthyear[1] . "'
					 AND a.attd_user_id = " . $userid . "
					) AS data RIGHT JOIN calendar c ON data.attd_date = c.calendar_date
			WHERE c.calendar_year = '" . $currentmonthyear[0] . "' AND c.calendar_month = '" . $currentmonthyear[1] . "'";

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

function getmail($mail, $mysqli)
{
	$query = "SELECT * FROM user WHERE user_id='" . $mail . "'";

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

function getusername($u_id, $mysqli)
{
	$query = "SELECT * FROM user WHERE user_id=$u_id";

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


function getHeaders($mailid, $mysqli)
{
	$query = "SELECT mail_to, mail_cc, mail_bcc FROM mail WHERE mail_inuse = $mailid LIMIT 0,1";

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

function getMailEach($userid, $mysqli)
{
	$query = "SELECT email FROM user WHERE user_id ='" . $userid . "'";
	//echo $query;
	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($m);
	$stmt->fetch();

	return $m;
}

function MailList($to, $ext = 'to', $mysqli)
{
	if ($ext == 'to') {
		$ml = explode(":", $to[0]['mail_to']);
	} else if ($ext == 'cc') {
		$ml = explode(":", $to[0]['mail_cc']);
	} else if ($ext == 'bcc') {
		$ml = explode(":", $to[0]['mail_bcc']);
	}

	$mlarr = array();
	for ($i = 0; $i < count($ml); $i++) {

		$mlarr[] = getMailEach($ml[$i], $mysqli);
	}

	return $mlarr;
}

function insert_mtime($array, $mysqli)
{
	$query = "UPDATE attendance SET attd_out_time='" . $array['gout'] . "', attd_comment_out = '" . $array['attd_comment'] . "', show_flag = 1 ";
	$query .= "WHERE attd_user_id = " . $array['userid'] . " AND attd_date = '".$array['attd_date']."'";

	$stmt = $mysqli->prepare($query);
	if ($stmt->execute()) {
		return true;
	}

	return false;
}