<?php

session_start();
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("lib/ini.functions.php");
include_once("ini.dbstring.php");

include_once("mod.select.php");
include_once("mod.calendar.php");
include_once("mod.attendance.php");
include_once("ctrl.checklogin.php");
include_once("ctrl.calendar.php");
include_once("ctrl.attendance.php");


mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8_general_ci'");


/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

//$userid = (!isset($_GET['userid']) || $_GET['userid'] == "")?1:$_GET['userid'];
$userid = $_GET['uid'];
$todaydate = explode("-", date("Y-n-j"));
$getCurrentMonth = getCurrentMonth($todaydate, $userid, $db);
$getname = getusername($userid, $db);
foreach ($getname as $result) {
	$fname = $result['user_name'];
}

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once('Classes/PHPExcel.php');


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
	$latetimediff = date_diff(date_create($row["attd_in_time"]), date_create("09:30"));
	$latetime = $latetimediff->format("%H:%I");
	if ($row["attd_in_time"] != "") {
		$intime = date("H:i", strtotime($row["attd_in_time"]));
	} else {
		$intime = "-";
	}
	//echo $latetime;
	if ($row["attd_out_time"] != "") {
		$outtime = date("H:i", strtotime($row["attd_out_time"]));
	} else {
		$outtime = "-";
	}
	if ($row["attd_out_time"] != "" && $row["attd_in_time"] != "") {
		$worktimediff = date_diff(date_create($row["attd_out_time"]), date_create($row["attd_in_time"]));
		$worktime = $worktimediff->format("%H:%I");
	} else {
		$worktime = "-";
	}
	if ($row["attd_out_time"] != "" && strtotime($row["attd_out_time"]) > strtotime("18:30")) {
		$overtimediff = date_diff(date_create($row["attd_out_time"]), date_create("18:30"));
		$overtime = $overtimediff->format("%H:%I");
	} else {
		$overtime = "-";
	}
	if ($row["attd_out_time"] != "" && strtotime($row["attd_out_time"]) < strtotime("18:30")) {
		$earlytimediff = date_diff(date_create($row["attd_out_time"]), date_create("18:30"));
		$earlytime = $earlytimediff->format("%H:%I");

	} else {
		$earlytime = "-";
	}
	if ($worktime != "-" && $overtime != "-") {
		$worktimecal = strtotime($worktime);
		$overtimecal = strtotime($overtime);
		$min = date("i", $overtimecal);
		$sec = date("s", $overtimecal);
		$hr = date("H", $overtimecal);

		$convert = strtotime("+$min minutes", $worktimecal);
		//$convert = strtotime("+$sec seconds", $convert);
		$convert = strtotime("+$hr hours", $convert);

		$totaltime = date("H:i", $convert);
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

//$objWriter->SaveAs($strPath."/".$filename);
exit;