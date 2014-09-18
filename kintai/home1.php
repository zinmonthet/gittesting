<?php
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("lib/ini.functions.php");
include_once ("ini.dbstring.php");

include_once ("mod.select.php");
include_once ("mod.calendar.php");
include_once ("mod.attendance.php");
include_once ("ctrl.checklogin.php");
include_once ("ctrl.calendar.php");
include_once ("ctrl.attendance.php");

sec_session_start();
if(!$_SESSION['sess_user_id']){
header('Location: index.php');
}
//$userid = (!isset($_GET['userid']) || $_GET['userid'] == "")?1:$_GET['userid'];
$userid=$_SESSION['sess_user_id'];

$todaydate = explode("-", date("Y-n-j"));
$getCurrentMonth = getCurrentMonth($todaydate, $userid, $db);
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Kintai system</title>
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
		elseif ($_GET['msg']=="2") {
			echo "<script>alert('Your profile has been changed.');</script>";
		}
	?>
		<?php
		include ('header.php');
		?>
		<div class="bd_content">
			<?php
			include ('left_menu.php');
			?>
			<div class="dat_content">

				<div class="container">
					<div class="search_bar">
						<ul class="dasu">
							<li class="print">
								<a href="" onclick="PrintDiv();">印刷</a>
								
							</li>
							<li class="mail">
								<a href="mail_test.php">メール</a>
							</li>
						</ul>
					</div>

					<div class="data_tbl" id="divToPrint">
						<table id="attd" style="width: 100%;">
							<tr>
								<th style="padding:10px;font-size: 90%;border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background-color: #514F4F;color:#E1E1E1;">日付</th>
								<th style="padding:10px;font-size: 90%;border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background-color: #514F4F;color:#E1E1E1;">曜日</th>	
								<th style="padding:10px;font-size: 90%;border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background-color: #514F4F;color:#E1E1E1;">出社時間</th>
								<th style="padding:10px;font-size: 90%;border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background-color: #514F4F;color:#E1E1E1;">遅刻</th>
								<th style="padding:10px;font-size: 90%;border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background-color: #514F4F;color:#E1E1E1;">退社時間</th>
								<th style="padding:10px;font-size: 90%;border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background-color: #514F4F;color:#E1E1E1;">作業時間</th>
								<th style="padding:10px;font-size: 90%;border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background-color: #514F4F;color:#E1E1E1;">残業時間</th>
								<th style="padding:10px;font-size: 90%;border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background-color: #514F4F;color:#E1E1E1;">統計時間</th>
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
									$in=strtotime($getCurrentMonth[$r]["attd_in_time"]);
									$out=strtotime($getCurrentMonth[$r]["attd_out_time"]);
									$worktime=$out-$in;
									$worktime=date('h:i',$worktime);
								} else {
									$worktime = "-";
								}

								// calculate overtime
								if ($getCurrentMonth[$r]["attd_out_time"] != "" && strtotime($getCurrentMonth[$r]["attd_out_time"]) > strtotime("18:30")) {
									//$overtimediff = date_diff(date_create($getCurrentMonth[$r]["attd_out_time"]), date_create("18:30"));
									//$overtime = $overtimediff -> format("%H:%I");
									$overtime=$out-strtotime("18:30");
									$overtime=date('h:i',$overtime);
								} else {
									$overtime = "-";
								}
								
								// calculate late time
								if ($getCurrentMonth[$r]["attd_in_time"] != "" && strtotime($getCurrentMonth[$r]["attd_in_time"]) >= strtotime("09:30")) {
									//$latetimediff = date_diff(date_create($getCurrentMonth[$r]["attd_in_time"]), date_create("09:30"));
									//$latetime = $latetimediff -> format("%H:%I");
									$in_time=strtotime($getCurrentMonth[$r]["attd_in_time"]);
									$limit=strtotime("09:30");
									$latetime=$in_time-$limit;
									$latetime=date('h:i',$latetime);
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
								echo "<td style='padding: 5px 10px;font-size: 90%;border-bottom: 1px dotted #514F4F;'>" . substr($getCurrentMonth[$r]["calendar_date"], -2) . "</td>";
								echo "<td style='padding: 5px 10px;font-size: 90%;border-bottom: 1px dotted #514F4F;'>" . strtoupper($getCurrentMonth[$r]["calendar_day"]) . "</td>";
								echo "<td style='padding: 5px 10px;font-size: 90%;border-bottom: 1px dotted #514F4F; text-align:center;'>" . $intime . "</td>";
								echo "<td style='padding: 5px 10px;font-size: 90%;border-bottom: 1px dotted #514F4F; text-align:center;'><span class='late'>" . $latetime . "</span></td>";
								echo "<td style='padding: 5px 10px;font-size: 90%;border-bottom: 1px dotted #514F4F; text-align:center;'>" . $outtime . "</td>";
								echo "<td style='padding: 5px 10px;font-size: 90%;border-bottom: 1px dotted #514F4F; text-align:center;'>" . $worktime . "</td>";
								echo "<td style='padding: 5px 10px;font-size: 90%;border-bottom: 1px dotted #514F4F; text-align:center;'>" . $overtime . "</td>";
								echo "<td style='padding: 5px 10px;font-size: 90%;border-bottom: 1px dotted #514F4F; text-align:center;'>" . $totaltime . "</td>";
								echo "</tr>";
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
