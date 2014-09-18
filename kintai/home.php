<?php
//error_reporting(E_ALL);
//ini_set( 'display_errors','1');

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
include_once("ctrl.checklogin.php");
include_once("ctrl.calendar.php");
include_once("ctrl.attendance.php");

$userid = $_SESSION['sess_user_id'];

$todaydate = explode("-", date("Y-n-j"));
$getCurrentMonth = getCurrentMonth($todaydate, $userid, $db);

$WORKINTIME = $_SESSION['sess_user_intime'];
$WORKOUTTIME = $_SESSION['sess_user_outtime'];
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Kintai system</title>
	<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo CSS; ?>/print.css" rel="stylesheet" type="text/css" media="print"/>
	<script src="<?php echo JS; ?>/jquery.min.js"></script>
</head>
<body>
<?php
if ($_GET['msg'] == "1") {
	echo "<script>alert('Mail has been sent');</script>";
} elseif ($_GET['msg'] == "2") {
	echo "<script>alert('Your profile has been changed.');</script>";
}
?>
<?php include('header.php'); ?>
<div class="bd_content">
<?php include('left_menu.php'); ?>
<div class="dat_content">
	<div class="container">
		<div class="search_bar">
			<ul class="dasu">
				<li>
					<a class="button" href=""
					   onclick="javascript:PrintDiv('divToPrint', '<?php echo CSS; ?>/import.css');">印刷</a>
				</li>
<!--				<li>-->
<!--					<a class="button" href="mail_test.php">メール</a>-->
<!--				</li>-->
			</ul>
		</div>
		<div class="info">
			<table>
				<tr>
					<td><span class="if"><?php echo $late; ?></span><span class="it">遅刻</span></td>
					<td><span class="if"><?php echo $earlyleave; ?></span><span class="it">早退</span></td>
					<td><span class="if"><?php echo $absent; ?></span><span class="it">欠勤</span></td>
				</tr>
			</table>
		</div>
		<div class="tblCalendarCtn" id="divToPrint">
			<table id="attd" class="tbl_str">
				<tr>
					<th class="left">日付</th>
					<th class="left">曜日</th>
					<th class="right">出社時間</th>
					<th class="right">退社時間</th>
					<th class="right">遅刻</th>
					<th class="right">早退</th>
					<th class="right">作業時間</th>
					<th class="right">残業時間</th>
					<th class="right">統計時間</th>
				</tr>
				<?php
				for ($r = 0; $r < count($getCurrentMonth); $r++) {
					// extract only current logged in user's data
					$overtime = "";
					$worktime = "";
					$totaltime = "";
					$intime = "";
					$outtime = "";
					$latetime = "";
					$commentIn = "";
					$commentOut = "";
					$total_wtime = 0;
					$in = $getCurrentMonth[$r]["attd_in_time"];
					$out = $getCurrentMonth[$r]["attd_out_time"];

					if ($getCurrentMonth[$r]["attd_in_time"] != "") {
						$intime = $getCurrentMonth[$r]["attd_in_time"];
					} else {
						$intime = "-";
					}

					if ($getCurrentMonth[$r]["attd_out_time"] != "" && $getCurrentMonth[$r]["attd_out_time"] != "00:00:00") {
						$outtime = $getCurrentMonth[$r]["attd_out_time"];
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
						$hours = floor($seconds / 3600);
						$minutes = floor(($seconds / 60) % 60);
						$seconds = $seconds % 60;

						$worktime = $hours . ":" . $minutes . ":" . $seconds;
					} else {
						$worktime = "-";
					}

					// calculate overtime
					if ($getCurrentMonth[$r]["attd_out_time"] != ""
						&& $getCurrentMonth[$r]["attd_out_time"] > $WORKOUTTIME
						&& $getCurrentMonth[$r]["attd_out_time"] != "00:00:00"
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

					// calculate late time
					if ($getCurrentMonth[$r]["attd_in_time"] != ""
						&& strtotime($getCurrentMonth[$r]["attd_in_time"]) >= strtotime($WORKINTIME)
					) {

						list($hours, $minutes, $sec) = explode(':', $in);
						$startTimestamp = mktime($hours, $minutes, $sec);

						list($hours, $minutes, $sec) = explode(':', $WORKINTIME);
						$endTimestamp = mktime($hours, $minutes, $sec);

						$seconds = $startTimestamp - $endTimestamp;
						// echo $seconds;
						$hours = floor($seconds / 3600);
						$minutes = floor(($seconds / 60) % 60);
						$seconds = $seconds % 60;
						$latetime = $hours . ":" . $minutes . ":" . $seconds;

					} else {
						$latetime = "-";
					}

					// calculate totaltime
					if ($worktime != "-" || $overtime != "-") {
						$totaltime = $worktime;
					} else {
						$totaltime = "-";
					}

					// calculate early time
					if ($getCurrentMonth[$r]["attd_out_time"] != ""
						&& $getCurrentMonth[$r]["attd_out_time"] < $WORKOUTTIME
						&& $getCurrentMonth[$r]["attd_out_time"] != "00:00:00"
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

					// add comment if have (IN)
					if ($getCurrentMonth[$r]['attd_comment_in'] != "") {
						$commentIn = "<a class='cmt' href='#' onclick='javascript:alert(\"".$getCurrentMonth[$r]['attd_comment_in']."\")'>(?)</a>";
					}

					// add comment if have (OUT)
					if ($getCurrentMonth[$r]['attd_comment_out'] != "") {
						$commentOut = "<a class='cmt' href='#' onclick='javascript:alert(\"".$getCurrentMonth[$r]['attd_comment_out']."\")'>(?)</a>";
					}

					// calculate total worktime
					if($worktime != "-") {
						list($h, $m, $s) = explode(':', $worktime);
						$ht += $h * 3600;
						$mt += $m * 60;
						$st += $s;
					}

					// calculate total overtime
					if($overtime != "-") {
						list($ho, $mo, $so) = explode(':', $overtime);
						$hto += $ho * 3600;
						$mto += $mo * 60;
						$sto += $so;
					}

					echo "<tr class='" . $class . "'>";
					echo "<td class='left'>" . substr($getCurrentMonth[$r]["calendar_date"], -2) . "</td>";
					echo "<td class='left'>" . changejpday($getCurrentMonth[$r]["calendar_day"]) . "</td>";
					echo "<td class='right'>$commentIn " . $intime . "</td>";
					echo "<td class='right'>$commentOut " . $outtime . "</td>";
					echo "<td class='right'><span class='late'>" . $latetime . "</span></td>";
					echo "<td class='right'><span class='early'>" . $earlytime . "</span></td>";
					echo "<td class='right worktime'>" . $worktime . "</td>";
					echo "<td class='right overtime'>" . $overtime . "</td>";
					echo "<td class='right'>" . $totaltime . "</td>";
					echo "</tr>";

					// calculate and format total working time and overtime
					$ttimestamp = $ht + $mt + $st;
					$th = floor($ttimestamp / 3600);
					$tm = floor(($ttimestamp / 60) % 60);
					$ts = $ttimestamp % 60;

					$ottimestamp = $hto + $mto + $sto;
					$oth = floor($ottimestamp / 3600);
					$otm = floor(($ottimestamp / 60) % 60);
					$ots = $ottimestamp % 60;
				}
				?>
				<tr>
					<td colspan="5"></td>
					<td class="right">統計時間</td>
					<td class="right">
						<?php echo $th . ":" . $tm . ":" . $ts; ?><br/>
						<span class="notice2">残業時間含み</span>
					</td>
					<td class="right">
						<?php echo $oth . ":" . $otm . ":" . $ots; ?><br/>
						<span class="notice2">残業時間</span>
					</td>
					<td></td>
				</tr>
				<tr>
					<th colspan="9" height="20"></th>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php
include_once('right_menu.php');
include_once("scripts.php");
?>
</div>
</body>
</html>
