<?php
function getpsw($name) {
	//echo $name;
	$sql = 'SELECT * FROM user WHERE user_name="' . $name . '"';
	//echo $sql;
	$result = mysql_query($sql) or die('There is no data') . mysql_error();
	return $result;
}

function getuser($year, $month, $mysqli) {
	if ($year != "" || $month != "") {
		if ($year && $month) {
			$sql = "SELECT * FROM attendance WHERE YEAR(create_date) = " . $year . " AND MONTH(create_date) = " . $month;
		} elseif ($year != "") {
			$sql = "SELECT * FROM attendance WHERE YEAR(create_date) = " . $year;
		} else {
			$sql = "SELECT * FROM attendance WHERE MONTH(create_date) = " . $month;
		}
	} else {
		$sql = "SELECT * FROM attendance";
	}
	if ($stmt = $mysqli -> query($sql)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function getAlluser($mysqli) {

	$sql = "SELECT * FROM attendance";
	if ($stmt = $mysqli -> query($sql)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}
?>
