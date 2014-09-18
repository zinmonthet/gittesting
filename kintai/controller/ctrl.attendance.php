<?php
if (isset($_POST["form_submit"]) && $_POST["form_submit"] == "true" && $_POST["form_submit"] != "") {
	$userid = trim($_POST['userid']);
	$data['attd_in_time'] = trim($_POST['attd_in_time']);
	$data['attd_date'] = trim($_POST['attd_date']);
	$data['attd_comment'] = trim($_POST['attd_comment']);

	if (checkInCheck($userid, $data, $db)) { // check whether checked in or not
		$rfc = "<script>alert('You already checked in for today, thank you.')</script>";
	} else {
		if (checkIn($userid, $data, $db)) { // checking in
			$rfc = "<script>alert('Successfully checked in, thank you.')</script>";
		} else {
			$rfc = "<script>alert('Sorry for inconvenience, try again.')</script>";
		}
	}
}

if (isset($_POST["form_submit_out"]) && $_POST["form_submit_out"] == "true" && $_POST["form_submit_out"] != "") {
	$userid = trim($_POST['userid']);
	$data['attd_out_time'] = trim($_POST['attd_out_time']);
	$data['attd_date'] = trim($_POST['attd_date']);
	$data['attd_comment'] = trim($_POST['attd_comment']);

	if (!checkOutCheckIn($userid, $data, $db)) { // check today check in data is exist or not
		$rfc = "<script>alert('Please, check in first, thank you.')</script>";
	} else {
		if (checkOutCheckedIn($userid, $data, $db)) { // check today checked in data is already exist or not
			$rfc = "<script>alert('You already checked out for today, thank you.')</script>";
		} else {
			if (checkOut($userid, $data, $db)) { // checking out
				$rfc = "<script>alert('Successfully checked out, thank you.')</script>";
			} else {
				$rfc = "<script>alert('Sorry for inconvenience, try again.')</script>";
			}
		}
	}
}

if (isset($_POST['filter']) && $_POST['filter'] == "true" && $_POST["filter"] != "") {
	$my[0] = trim($_POST['year']);
	$my[1] = trim($_POST['month']);
	$my[2] = trim($todaydate[2]);

	// only allow to view history
	$show = true;
	if ($my[0] > $todaydate[0]) {
		$show = false;
	} else if ($my[0] == $todaydate[0]) {
		if ($my[1] > $todaydate[1]) {
			$show = false;
		}
	}

	$getCurrentMonth = getCurrentMonth($my, $userid, $db);
	$filter_late = getLateCheckIn($userid, $WORKINTIME, $my, $db);
	$filter_earlyleave = getEarlyCheckOut($userid, $WORKOUTTIME, $my, $db);
	$filter_absent = getAbsent($userid, $my, $db);
}

if (isset($_POST['filterAdmin']) && $_POST['filterAdmin'] == "true" && $_POST["filterAdmin"] != "") {
	$todaydate = explode("-", date("Y-n-j"));
	$my[0] = trim($_POST['year']);
	$my[1] = trim($_POST['month']);
	$my[2] = $todaydate[2];

	// only allow to view history
	$show = true;
	if ($my[0] > $todaydate[0]) {
		$show = false;
	} else if ($my[0] == $todaydate[0]) {
		if ($my[1] > $todaydate[1]) {
			$show = false;
		}
	}
}

if (isset($_POST['sub_leave'])) {
	$_POST['gout_hrs'] = trim($_POST['gout_hrs'] == "" ? 0 : $_POST['gout_hrs']);
	$_POST['gout_min'] = trim($_POST['gout_min'] == "" ? 0 : $_POST['gout_min']);
	$_POST['gout_sec'] = trim($_POST['gout_sec'] == "" ? 0 : $_POST['gout_sec']);

	$err = array();

	// validation
	// don't allow 01:00:50
	if ($_POST['gout_hrs'] != 0 && $_POST['gout_min'] == 0 && $_POST['gout_sec'] != 0) {
		$err['gout_err'] = "true";
	}

	// don't allow blank date
	if (!isset($_POST['gout_date']) && $_POST['gout_date'] == "") {
		$err['gout_err'] = "true";
	}

	// dount' allow all blank
	if (($_POST['gout_hrs'] == 0 || $_POST['gout_hrs'] == "")
		&& ($_POST['gout_min'] == 0 || $_POST['gout_min'] == "")
		&& ($_POST['gout_sec'] == 0 || $_POST['gout_sec'] == "")
	) {
		$err['gout_err'] = "true";
	}

	$data['gout'] = $_POST['gout_hrs'] . ":" . $_POST['gout_min'] . ":" . $_POST['gout_sec'];
	$data['userid'] = $_SESSION['sess_user_id'];
	$data['gdate'] = $_POST['gout_date'];
	$data['attd_date'] = $_POST['gout_date'];
	$data['attd_comment'] = $_POST['attd_comment'];

	// if no error
	if (empty($err)) {
		if (!checkOutCheckIn($data['userid'], $data, $db)) { // check today check in data is exist or not
			$rfc = "<script>alert('Please, check in first, thank you.')</script>";
		} else {
			if (checkOutCheckedIn($data['userid'], $data, $db)) { // check today checked in data is already exist or not
				$rfc = "<script>alert('You already checked out for today, thank you.')</script>";
			} else {
				if (insert_mtime($data, $db)) { // checking out
					$rfc = "<script>alert('Successfully checked out, thank you.')</script>";
				} else {
					$rfc = "<script>alert('Sorry for inconvenience, try again.')</script>";
				}
			}
		}
	} else {
		$rfc = "<script>alert('Enter hours, minutes, seconds and date correctly.')</script>";
	}
}