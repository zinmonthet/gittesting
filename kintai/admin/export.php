<?php

include_once("../lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("mod.group.php");
include_once("mod.select.php");
include_once("ctrl.checklogin.php");
include_once("mod.attendance.php");
include_once("ctrl.attendance.php");
include_once("ctrl.group.php");
//error_reporting(E_ALL);
//ini_set( 'display_errors','1');

mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8_general_ci'");

//$userid = (!isset($_GET['userid']) || $_GET['userid'] == "")?1:$_GET['userid'];
$userid = $_GET['uid'];
$todaydate = explode("-", date("Y-n-j"));
$getCurrentMonth = getCurrentMonth($todaydate, $userid, $db);
$getname = getusername($userid, $db);
$wtime = getGroupTime($userid, $db);
$WORKINTIME = $wtime[0]['group_intime'];
$WORKOUTTIME = $wtime[0]['group_outtime'];
//echo "testing".$WORKINTIME;
foreach ($getname as $result) {
	$fname = $result['user_name'];
}

if (PHP_SAPI == 'cli') {
	die('This example should only be run from a Web Browser');
}
/** Include PHPExcel */
require_once('../Classes/PHPExcel.php');


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Rubbersoul")
	->setLastModifiedBy("Maarten Balliauw")
	->setTitle("Office 2007 XLSX Test Document")
	->setSubject("Office 2007 XLSX Test Document")
	->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
	->setKeywords("office 2007 openxml php")
	->setCategory("Test result file");

$hrow = 1;

$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A' . $hrow, '日付')
	->setCellValue('B' . $hrow, '曜日')
	->setCellValue('C' . $hrow, '出社時間')
	->setCellValue('D' . $hrow, '遅刻')
	->setCellValue('E' . $hrow, '退社時間')
	->setCellValue('F' . $hrow, '早退')
	->setCellValue('G' . $hrow, '作業時間')
	->setCellValue('H' . $hrow, '残業時間')
	->setCellValue('I' . $hrow, '統計時間');

foreach ($getCurrentMonth as $row) {
	$hrow = $hrow + 1;
	$in = $row["attd_in_time"];
	$out = $row["attd_out_time"];
	$overtime = "";
	$worktime = "";
	$totaltime = "";
	$intime = "";
	$outtime = "";
	$latetime = "";
	if ($row["attd_in_time"] != "") {
		$intime = $row["attd_in_time"];
	} else {
		$intime = "-";
	}
	//echo $intime;
	if ($row["attd_out_time"] != "") {
		$outtime = $row["attd_out_time"];
	} else {
		$outtime = "-";
	}
	if ($row["attd_in_time"] != "" && strtotime($row["attd_in_time"]) >= strtotime($WORKINTIME)) {
		list($hours, $minutes, $sec) = explode(':', $in);
		$startTimestamp = mktime($hours, $minutes, $sec);

		list($hours, $minutes, $sec) = explode(':', $WORKINTIME);
		$endTimestamp = mktime($hours, $minutes, $sec);

		$seconds = $startTimestamp - $endTimestamp;
		$hours = floor($seconds / 3600);
		$minutes = floor($seconds / 60) % 60;
		$seconds = $seconds % 60;
		$latetime = $hours . ":" . $minutes . ":" . $seconds;

	} else {
		$latetime = "-";
	}
	if ($row["attd_out_time"] != "" && $row["attd_in_time"] != "" && $row["attd_out_time"] != "00:00:00") {
		list($hours, $minutes, $sec) = explode(':', $in);
		$startTimestamp = mktime($hours, $minutes, $sec);

		list($hours, $minutes, $sec) = explode(':', $out);
		$endTimestamp = mktime($hours, $minutes, $sec);

		$seconds = $endTimestamp - $startTimestamp;
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds / 60) % 60);
		$seconds = $seconds % 60;
		$worktime = $hours . ":" . $minutes . ":" . $seconds;
	} else {
		$worktime = "-";
	}
	if ($row["attd_out_time"] != "" &&
		strtotime($row["attd_out_time"]) > strtotime($WORKOUTTIME)
		&& $row["attd_out_time"] != "00:00:00"
	) {
		list($hours, $minutes, $sec) = explode(':', $out);
		$startTimestamp = mktime($hours, $minutes, $sec);

		list($hours, $minutes, $sec) = explode(':', $WORKOUTTIME);
		$endTimestamp = mktime($hours, $minutes, $sec);

		$seconds = $startTimestamp - $endTimestamp;
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds / 60) % 60);
		$seconds = $seconds % 60;

		$overtime = $hours . ":" . $minutes . ":" . $seconds;
	} else {
		$overtime = "-";
	}
	if ($row["attd_out_time"] != ""
		&& strtotime($row["attd_out_time"]) < strtotime($WORKOUTTIME)
		&& $row["attd_out_time"] != "00:00:00"
	) {
		list($hours, $minutes, $sec) = explode(':', $out);
		$startTimestamp = mktime($hours, $minutes, $sec);

		list($hours, $minutes, $sec) = explode(':', $WORKOUTTIME);
		$endTimestamp = mktime($hours, $minutes, $sec);

		$seconds = $endTimestamp - $startTimestamp;
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds / 60) % 60);
		$seconds = $seconds % 60;
		$earlytime = $hours . ":" . $minutes . ":" . $seconds;

	} else {
		$earlytime = "-";
	}

	if ($worktime != "-" || $overtime != "-") {
		$totaltime = $worktime;
	} else {
		$totaltime = "-";
	}
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A' . $hrow, substr($row["calendar_date"], -2))
		->setCellValue('B' . $hrow, strtoupper($row["calendar_day"]))
		->setCellValue('C' . $hrow, $intime)
		->setCellValue('D' . $hrow, $latetime)
		->setCellValue('E' . $hrow, $outtime)
		->setCellValue('F' . $hrow, $earlytime)
		->setCellValue('G' . $hrow, $worktime)
		->setCellValue('H' . $hrow, $overtime)
		->setCellValue('I' . $hrow, $totaltime);
}

$filename = $fname . "_attendance.xlsx";
$strPath = 'localhost/kintai/download';
$objPHPExcel->getActiveSheet()->setTitle('attendance');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Encoding: UTF-8');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;