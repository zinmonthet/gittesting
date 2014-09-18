<?php
// *** create new group ***
if(isset($_POST['gadd']) && $_POST['gadd'] == "true" && $_POST['gadd'] != "") {
	$data['gname'] = trim($_POST['gname']);
	$_POST['gin_hrs'] =  trim($_POST['gin_hrs'] == ""?0:$_POST['gin_hrs']);
	$_POST['gin_min'] =  trim($_POST['gin_min'] == ""?0:$_POST['gin_min']);
	$_POST['gin_sec'] =  trim($_POST['gin_sec'] == ""?0:$_POST['gin_sec']);

	$_POST['gout_hrs'] =  trim($_POST['gout_hrs'] == ""?0:$_POST['gin_hrs']);
	$_POST['gout_min'] =  trim($_POST['gout_min'] == ""?0:$_POST['gin_min']);
	$_POST['gout_sec'] =  trim($_POST['gout_sec'] == ""?0:$_POST['gin_sec']);

	$err = array();

	// validation
	if(!isset($_POST['gname']) || $_POST['gname'] == "") {
		$err['gname'] = "Please enter your group name";
	}

	// don't allow 01:00:50
	if($_POST['gin_hrs'] != 0 && $_POST['gin_min'] == 0 && $_POST['gin_sec'] != 0 ) {
		$err['gin_err'] = "Please make sure minute is set if hour and second is set.";
	}

	// don't allow 01:00:50
	if($_POST['gout_hrs'] != 0 && $_POST['gout_min'] == 0 && $_POST['gout_sec'] != 0 ) {
		$err['gout_err'] = "Please make sure minute is set if hour and second is set.";
	}

	$data['gin'] = $_POST['gin_hrs'] . ":" . $_POST['gin_min'] . ":" . $_POST['gin_sec'];
	$data['gout'] = $_POST['gout_hrs'] . ":" . $_POST['gout_min'] . ":" . $_POST['gout_sec'];

	// if no error
	if(empty($err)) {
		if(insertGroup($data, $db)) {
			echo "<script>alert('User group successfully added')</script>";
		}else {
			header("location: " . ROOT . "error.html");
			exit;
		}
	}else {
		echo "<script>alert('Enter group name, hours, minutes and seconds corrrectly.')</script>";
	}
}

// *** edit group ***
if(isset($_POST['gedit']) && $_POST['gedit'] == "true" && $_POST['gedit'] != "") {
	$data['gname'] = trim($_POST['gname']);
	$data['gin'] = trim($_POST['gin']);
	$data['gout'] = trim($_POST['gout']);
	$data['gid'] = trim($_POST['gid']);

	$err = array();

	// validation
	if(!isset($_POST['gname']) || $_POST['gname'] == "") {
		$err['gname'] = "Please enter your group name";
	}

	if(!isset($_POST['gin']) || $_POST['gin'] == "") {
		$err['gin'] = "Please set your work in time for the group";
	}

	if(!isset($_POST['gout']) || $_POST['gout'] == "") {
		$err['gout'] = "Please set your work out time for the group";
	}

	// if no error
	if(empty($err)) {
		if(updateGroup($data, $db)) {
			echo "<script>alert('User group successfully updated')</script>";
		}else {
			header("location: " . ROOT . "error.html");
			exit;
		}
	}else {
		echo "<script>alert('In time or out time can not be blank or misinsert.')</script>";
	}
}

