<?php
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("ini.dbstring.php");
include_once ("lib/ini.functions.php");
include_once ("ctrl.checklogin.php");
sec_session_start();
//$userid = (!isset($_GET['userid']) || $_GET['userid'] == "") ? 1 : $_GET['userid'];
$userid=$_SESSION['sess_user_id'];
$todaydate = explode("-", date("Y-n-j"));
$show = true;

include_once ("mod.calendar.php");
include_once ("mod.attendance.php");
include_once ("ctrl.checklogin.php");
include_once ("ctrl.calendar.php");
include_once ("ctrl.attendance.php");

if(!$_SESSION['sess_user_id']){
header('Location: index.php');
}

if (!isset($_POST['filter']) && $_POST['filter'] != "true" && $_POST["filter"] == "") {
	$getCurrentMonth = getCurrentMonth($todaydate,$userid, $db);
	$filter_late = getLateCheckIn($userid, $todaydate, $db);
	$filter_earlyleave = getEarlyCheckOut($userid, $todaydate, $db);
	$filter_absent = getAbsent($userid, $todaydate, $db);
}

	$month=$_POST['month'];
	$year=$_POST['year'];
	//$yr['0']=$year;
	//$yr['1']=$month;
	$_SESSION['year'] = $year;
	$_SESSION['month'] = $month;
	//echo $_SESSION['year'];

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Kinntai system</title>
		<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo JS; ?>/jquery.js"></script>
		<script type="text/javascript">     
        function PrintDiv() {    
           var divToPrint = document.getElementById('divToPrint');
           var popupWin = window.open('', '_blank', 'width=300,height=300');
           popupWin.document.open();
           popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
            popupWin.document.close();
                }
     </script>
	</head>
	<body>
	<?php
		if($_GET['msg']=="1")
		{
			echo "<script>alert('Mail has been sent');</script>";
		}
		
	?>
		<?php include ('header.php'); ?>
		<div class="bd_content">
			<?php include ('left_menu.php'); ?>
			<div class="dat_content">
				<div class="container">
					<div class="search_bar">
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<span id='filter'>
								<select class="button2" name="year" id="name">
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2015">2016</option>
								</select> 年
								<select class="button2" name="month" id="month">
									<option value="1">JAN</option>
									<option value="2">FEB</option>
									<option value="3">MAR</option>
									<option value="4">APR</option>
									<option value="5">MAY</option>
									<option value="6">JUN</option>
									<option value="7">JUL</option>
									<option value="8">AUG</option>
									<option value="9">SEP</option>
									<option value="10">OCT</option>
									<option value="11">NOV</option>
									<option value="12">DEC</option>
								</select> 月
								<input type="hidden" name="filter" value="true">
								<input class="button" type="submit" value="検索" name="btn_search" id="btn_search">
							</span>
						</form>
						<ul class="dasu">
							<li class="print">
								<a href="" onclick="PrintDiv();">印刷</a>
							</li>
							<li class="mail">
								<a href="search_mail.php">メール</a>
							</li>
						</ul>
					</div>
					<div class="data_tbl" id="divToPrint">
					<?php if($show) { ?>
						<table class="info">
							<tr>
								<td><span class="num"><?php echo $filter_late; ?></span>遅刻</td>
								
								<td><span class="num"><?php echo $filter_earlyleave; ?></span>早退</td>
								
								<td><span class="num"><?php echo $filter_absent; ?></span>欠勤</td>
							</tr>							
						</table>
						<br/>
						<table id="attd" class="tbl_str">
							<tr>
								<th class="left">日付</th>
								<th class="left">曜日</th>
								<th class="right">出社時間</th>
								<th class="right">遅刻</th>
								<th class="right">退社時間</th>
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
								$in=date("H:i", strtotime($getCurrentMonth[$r]["attd_in_time"]));
								$out=date("H:i", strtotime($getCurrentMonth[$r]["attd_out_time"]));
								if ($getCurrentMonth[$r]["attd_in_time"] != "") {
									$intime = date("H:i", strtotime($getCurrentMonth[$r]["attd_in_time"]));
								} else {
									$intime = "-";
								}
								
								if ($getCurrentMonth[$r]["attd_out_time"] != "") {
									$outtime = date("H:i", strtotime($getCurrentMonth[$r]["attd_out_time"]));
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
								if ($getCurrentMonth[$r]["attd_out_time"] != "" && $getCurrentMonth[$r]["attd_in_time"] != "") {
									//$worktimediff = date_diff(date_create($getCurrentMonth[$r]["attd_out_time"]), date_create($getCurrentMonth[$r]["attd_in_time"]));
									//$worktime = $worktimediff -> format("%H:%I");
									
									//$worktime=$out-$in;
									//$worktime=date('h:i',$worktime);
									//test
									list($hours, $minutes) = split(':', $in); 
									$startTimestamp = mktime($hours, $minutes); 
									 
									list($hours, $minutes) = split(':', $out); 
									$endTimestamp = mktime($hours, $minutes); 
									
									$seconds = $endTimestamp - $startTimestamp; 
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 
									//echo "Time passed: <b>$hours</b> hours and <b>$minutes</b> minutes<br>"; 
									//end test
									$worktime=$hours.":".$minutes;
								} else {
									$worktime = "-";
								}

								// calculate overtime
								if ($getCurrentMonth[$r]["attd_out_time"] != "" && strtotime($getCurrentMonth[$r]["attd_out_time"]) > strtotime("18:30")) {
									//$overtimediff = date_diff(date_create($getCurrentMonth[$r]["attd_out_time"]), date_create("18:30"));
									//$overtime = $overtimediff -> format("%H:%I");
									//$overtime=$out-strtotime("18:30");
									//$overtime=date('h:i',$overtime);
									$limit_ot="18:30";
									list($hours, $minutes) = split(':', $out); 
									$startTimestamp = mktime($hours, $minutes); 
									 
									list($hours, $minutes) = split(':', $limit_ot); 
									$endTimestamp = mktime($hours, $minutes); 
									
									$seconds = $startTimestamp - $endTimestamp; 
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 
									//echo "Time passed: <b>$hours</b> hours and <b>$minutes</b> minutes<br>"; 
									//end test
									$overtime=$hours.":".$minutes;
								} else {
									$overtime = "-";
								}
								
								// calculate late time
								if ($getCurrentMonth[$r]["attd_in_time"] != "" && strtotime($getCurrentMonth[$r]["attd_in_time"]) >= strtotime("09:30")) {
									//$latetimediff = date_diff(date_create($getCurrentMonth[$r]["attd_in_time"]), date_create("09:30"));
									//$latetime = $latetimediff -> format("%H:%I");
									$limit="09:30";
									//$latetime=$in-$limit;
									//$latetime=date('h:i',$latetime);
									list($hours, $minutes) = split(':', $in); 
									$startTimestamp = mktime($hours, $minutes); 
									 
									list($hours, $minutes) = split(':', $limit); 
									$endTimestamp = mktime($hours, $minutes); 
									
									$seconds = $startTimestamp - $endTimestamp; 
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 
									//echo "Time passed: <b>$hours</b> hours and <b>$minutes</b> minutes<br>"; 
									//end test
									$latetime=$hours.":".$minutes;
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
									//$convert = strtotime("+$sec seconds", $convert);
									$convert = strtotime("+$hr hours", $convert);

									$totaltime = date("H:i", $convert);
								} else {
									$totaltime = "-";
								}
								echo "<tr class='" . $class . "'>";
								echo "<td class='left'>" . substr($getCurrentMonth[$r]["calendar_date"], -2) . "</td>";
								echo "<td class='left'>" . strtoupper($getCurrentMonth[$r]["calendar_day"]) . "</td>";
								echo "<td class='right'>" . $intime . "</td>";
								echo "<td class='right'><span class='late'>" . $latetime . "</span></td>";
								echo "<td class='right'>" . $outtime . "</td>";
								echo "<td class='right'><span class='early'>" . $earlytime . "</span></td>";
								echo "<td class='right'>" . $worktime . "</td>";
								echo "<td class='right'>" . $overtime . "</td>";
								echo "<td class='right'>" . $totaltime . "</td>";
								echo "</tr>";
							}
							?>
						</table>
					</div>
					<?php }else { echo "No data"; }?>
				</div>
			</div>
			<?php include_once ("right_menu.php"); ?>
		</div>
	</body>
</html>
