<?php
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.login.php");

// check user role and authentication
checkSession($_SESSION['sess_user_role']);
checkLogin("user", $_SESSION['sess_user_role']);

include_once("mod.select.php");
include_once("mod.calendar.php");
include_once("mod.attendance.php");
include_once("ctrl.calendar.php");
include_once("ctrl.attendance.php");

if ($_SESSION['sess_user_id']) {
	$to = $_SESSION['sess_user_id'];
	$rsesult = getmail($to, $db);
	foreach ($rsesult as $row) {
		$tosend = $row['email'];
	}
}
$getname = getusername($_SESSION['sess_user_id'], $db);

foreach ($getname as $result) {
	$fname = $result['user_name'];
	$eid = $result['user_eid'];
}
$userid = (!isset($_GET['userid']) || $_GET['userid'] == "") ? 1 : $_GET['userid'];

$todaydate = explode("-", date("Y-n-j"));
$getCurrentMonth = getCurrentMonth($todaydate, $_SESSION['sess_user_id'], $db);

$body = '<html><head><title></title>';
$body .= '<style>';
$body .= 'td{text-align:center; border-bottom: 1px dotted #514F4F;}';
$body .= '</style>';
$body .= '</head><body>';
$body .= 'User Name: ' . $fname . '<br>';
$body .= 'User Id: ' . $eid . '<br>';
$body .= '<table style="width:800px">';
$body .= "<tr>";
$body .= '<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">日付</th>';
$body .= '<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">曜日</th>';
$body .= '<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">出社時間</th>';
$body .= '<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">遅刻</th>';
$body .= '<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">退社時間</th>';
$body .= '<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">作業時間</th>';
$body .= '<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">残業時間</th>';
$body .= '<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">統計時間</th>';
$body .= '</tr>';

for ($r = 0; $r < count($getCurrentMonth); $r++) {
	// extract only current logged in user's data
	$overtime = "";
	$worktime = "";
	$totaltime = "";
	$intime = "";
	$outtime = "";
	$latetime = "";
	$in = date("H:i:s", strtotime($getCurrentMonth[$r]["attd_in_time"]));
	$out = date("H:i:s", strtotime($getCurrentMonth[$r]["attd_out_time"]));

	if ($getCurrentMonth[$r]["attd_in_time"] != "") {
		$intime = date("H:i:s", strtotime($getCurrentMonth[$r]["attd_in_time"]));
	} else {
		$intime = "-";
	}

	if ($getCurrentMonth[$r]["attd_out_time"] != "" && $getCurrentMonth[$r]["attd_out_time"] != "00:00:00") {
		$outtime = date("H:i:s", strtotime($getCurrentMonth[$r]["attd_out_time"]));
	} else {
		$outtime = "-";
	}

	if ($getCurrentMonth[$r]["calendar_status"] == 0 && $getCurrentMonth[$r]["calendar_day"] == "sun") {
		$class = "offSun";
	} else if ($getCurrentMonth[$r]["calendar_status"] == 0 && $getCurrentMonth[$r]["calendar_day"] == "sat") {
		$class = "offSat";
	} else if ($getCurrentMonth[$r]["calendar_status"] == 0) {
		$class = "offHoliday";
	} else {
		$class = "";
	}

	// calculate worktime
	if ($getCurrentMonth[$r]["attd_out_time"] != ""
		&& $getCurrentMonth[$r]["attd_in_time"] != ""
		&& $getCurrentMonth[$r]["attd_out_time"] != "00:00:00"
	) {
		list($hours, $minutes, $sec) = explode(':', $in);
		$startTimestamp = mktime($hours, $minutes, $sec);

		list($hours, $minutes, $sec) = explode(':', $out);
		$endTimestamp = mktime($hours, $minutes, $sec);

		$seconds = $endTimestamp - $startTimestamp;
		$sec = $seconds % 60;
		$minutes = ($seconds / 60) % 60;
		$hours = round($seconds / (60 * 60));

		$worktime = $hours . ":" . $minutes . ":" . $sec;
	} else {
		$worktime = "-";
	}

	// calculate overtime
	if ($getCurrentMonth[$r]["attd_out_time"] != ""
		&& strtotime($getCurrentMonth[$r]["attd_out_time"]) > strtotime(WORKOUTTIME)
		&& $getCurrentMonth[$r]["attd_out_time"] != "00:00:00"
	) {
		$limit_ot = WORKOUTTIME;
		list($hours, $minutes, $sec) = explode(':', $out);
		$startTimestamp = mktime($hours, $minutes, $sec);

		list($hours, $minutes, $sec) = explode(':', $limit_ot);
		$endTimestamp = mktime($hours, $minutes, $sec);

		$seconds = $startTimestamp - $endTimestamp;
		$sec = $seconds % 60;
		$minutes = ($seconds / 60) % 60;
		$hours = round($seconds / (60 * 60));

		$overtime = $hours . ":" . $minutes . ":" . $sec;
	} else {
		$overtime = "-";
	}

	// calculate late time
	if ($getCurrentMonth[$r]["attd_in_time"] != "" && strtotime($getCurrentMonth[$r]["attd_in_time"]) >= strtotime(WORKINTIME)) {
		$limit = WORKINTIME;
		list($hours, $minutes, $sec) = explode(':', $in);
		$startTimestamp = mktime($hours, $minutes, $sec);

		list($hours, $minutes, $sec) = explode(':', $limit);
		$endTimestamp = mktime($hours, $minutes, $sec);

		$seconds = $startTimestamp - $endTimestamp;
		$sec = $seconds % 60;
		$minutes = ($seconds / 60) % 60;
		$hours = round($seconds / (60 * 60));
		$latetime = $hours . ":" . $minutes . ":" . $sec;
	} else {
		$latetime = "-";
	}

	// calculate totaltime
	if ($worktime != "-" && $overtime != "-") {
		$worktimecal = strtotime($worktime);
		$overtimecal = strtotime($overtime);
		$min = date("i", $overtimecal);
		$sec = date("s", $overtimecal);
		$hr = date("H", $overtimecal);

		$convert = strtotime("+$min minutes", $worktimecal);
		$convert = strtotime("+$hr hours", $convert);

		$totaltime = date("H:i", $convert);
	} else {
		$totaltime = "-";
	}

	// calculate early time
	if ($getCurrentMonth[$r]["attd_out_time"] != "" && strtotime($getCurrentMonth[$r]["attd_out_time"]) < strtotime(WORKOUTTIME) && $getCurrentMonth[$r]["attd_out_time"] != "00:00:00") {
		$limit = WORKOUTTIME;
		list($hours, $minutes, $sec) = explode(':', $out);
		$startTimestamp = mktime($hours, $minutes, $sec);

		list($hours, $minutes, $sec) = explode(':', $limit);
		$endTimestamp = mktime($hours, $minutes, $sec);

		$seconds = $endTimestamp - $startTimestamp;
		$sec = $seconds % 60;
		$minutes = ($seconds / 60) % 60;
		$hours = round($seconds / (60 * 60));
		$earlytime = $hours . ":" . $minutes . ":" . $sec;
	} else {
		$earlytime = "-";
	}

	$body .= "<tr class='" . $class . "'>";
	$body .=  "<td class='left'>" . substr($getCurrentMonth[$r]["calendar_date"], -2) . "</td>";
	$body .= "<td class='left'>" . strtoupper($getCurrentMonth[$r]["calendar_day"]) . "</td>";
	$body .= "<td class='right'>" . $intime . "</td>";
	$body .= "<td class='right'><span class='late'>" . $latetime . "</span></td>";
	$body .= "<td class='right'>" . $outtime . "</td>";
	$body .= "<td class='right'><span class='early'>" . $earlytime . "</span></td>";
	$body .= "<td class='right'>" . $worktime . "</td>";
	$body .= "<td class='right'>" . $overtime . "</td>";
	$body .= "<td class='right'>" . $totaltime . "</td>";
	$body .= "</tr>";
}
$body .= "</table></body></html>";

$from_name = $tosend;

$headers = "MIME-Version: 1.0 \n";
$headers .= 'From:' . $from_name . "\r\n";
$headers .= "Content-type: text/html;charset=ISO-2022-JP \n";

/* Convert body to same encoding as stated 
in Content-Type header above */

$body = mb_convert_encoding($body, "ISO-2022-JP", "AUTO");

/* Mail, optional paramiters. */
$subject = "Attendance of " . $fname;

mb_language("ja");
$subject = mb_convert_encoding($subject, "ISO-2022-JP", "AUTO");
$subject = mb_encode_mimeheader($subject);

//if (mail('j.sumitomo@rubbersoul.co.jp', $subject, $body, $headers)) {
//	header("Location:home.php?msg=1");
//}
if (mail('pyinyeinkyaw@rubbersoul.co.jp', $subject, $body, $headers)) {
	header("Location:home.php?msg=1");
	exit;
}