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

if(!$_SESSION['sess_user_id'] || $_SESSION['sess_user_role']=="0"){
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
        <script src="<?php echo JS; ?>/jquery-1.10.2.js" type="text/JavaScript" language="javascript"></script>
        <script src="<?php echo JS; ?>/jquery-ui-1.10.4.custom.js"></script>
        <script src="<?php echo JS; ?>/jquery.PrintArea.js" type="text/JavaScript" language="javascript"></script>

     

        <link type="text/css" rel="stylesheet" href="<?php echo CSS; ?>/PrintArea.css" />                <!-- Y : rel is stylesheet and media is in [all,print,empty,undefined] -->
        <link type="text/css" rel="stylesheet" href="media_all.css"  media="all" />   <!-- Y : rel is stylesheet and media is in [all,print,empty,undefined] -->
        <link type="text/css" rel=""           href="empty.css" />                    <!-- N : rel is not stylesheet -->
        <link type="text/css" rel="noPrint"    href="noPrint.css" />                  <!-- N : rel is not stylesheet -->
        <link type="text/css" rel="stylesheet" href="media_none.css" media="xyz" />   <!-- N : media not in [all,print,empty,undefined] -->
        <link type="text/css"                  href="no_rel.css"     media="print" /> <!-- N : no rel attribute -->
        <link type="text/css"                  href="no_rel_no_media.css"          /> <!-- N : no rel, no media attributes -->
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
	<?php //include ('header.php'); ?>
		<div class="bd_content">
			<?php
			include ('left_menu.php');
			?>

	<div class="dat_content">
				<div class="container">
					<div class="search_bar">
						<ul class="dasu">
							<li class="print">
								<a href="" class="b1" name="print" id="print">印刷</a>
								
							</li>
							<li class="mail">
								<a href="mail_test.php">メール</a>
							</li>
						</ul>
					</div>

					<div class="data_tbl" id="divToPrint">
						<table id="attd" style="width: 100%;" class="list">
							<tr>
								<th>日付</th>
								<th>曜日</th>
								<th>出社時間</th>
								<th>遅刻</th>
								<th>退社時間</th>
								<th>作業時間</th>
								<th>残業時間</th>
								<th>統計時間</th>

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
								$in=date("H:i:s", strtotime($getCurrentMonth[$r]["attd_in_time"]));
								$out=date("H:i:s", strtotime($getCurrentMonth[$r]["attd_out_time"]));
								if ($getCurrentMonth[$r]["attd_in_time"] != "") {
									$intime = date("H:i:s", strtotime($getCurrentMonth[$r]["attd_in_time"]));
								} else {
									$intime = "-";
								}
								
								if ($getCurrentMonth[$r]["attd_out_time"] != "") {
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
								if ($getCurrentMonth[$r]["attd_out_time"] != "" && $getCurrentMonth[$r]["attd_in_time"] != "") {
									//$worktimediff = date_diff(date_create($getCurrentMonth[$r]["attd_out_time"]), date_create($getCurrentMonth[$r]["attd_in_time"]));
									//$worktime = $worktimediff -> format("%H:%I");
									
									//$worktime=$out-$in;
									//$worktime=date('h:i',$worktime);
									//test
									list($hours, $minutes,$sec) = split(':', $in); 
									$startTimestamp = mktime($hours, $minutes,$sec); 
									 
									list($hours, $minutes,$sec) = split(':', $out); 
									$endTimestamp = mktime($hours, $minutes,$sec); 
									
									$seconds = $endTimestamp - $startTimestamp;
									$sec = $seconds % 60; 
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 
									//echo "Time passed: <b>$hours</b> hours and <b>$minutes</b> minutes<br>"; 
									//end test
									$worktime=$hours.":".$minutes.":".$sec;
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
									list($hours, $minutes,$sec) = split(':', $out); 
									$startTimestamp = mktime($hours, $minutes,$sec); 
									 
									list($hours, $minutes,$sec) = split(':', $limit_ot); 
									$endTimestamp = mktime($hours, $minutes,$sec); 
									
									$seconds = $startTimestamp - $endTimestamp; 
									$sec=$seconds % 60; 
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
									$limit="09:30:00";
									//$latetime=$in-$limit;
									//$latetime=date('h:i',$latetime);
									list($hours, $minutes,$sec) = split(':', $in); 
									$startTimestamp = mktime($hours, $minutes,$sec); 
									 
									list($hours, $minutes,$sec) = split(':', $limit); 
									$endTimestamp = mktime($hours, $minutes,$sec); 
									
									$seconds = $startTimestamp - $endTimestamp; 
									$sec = $seconds% 60;
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 
									//echo "Time passed: <b>$hours</b> hours and <b>$minutes</b> minutes<br>"; 
									//end test
									$latetime=$hours.":".$minutes.":".$sec;
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
   <?php
           include_once ('right_menu.php');
            ?>
		</div>

  <script>
    $(document).ready(function(){



        $("a.b1").click(function(){

            var mode = "popup";			
            var close = mode == "popup";
            var print = "";
			print="div.data_tbl";
			alert(print+"ssssssssssssssss");

  
            var options = { mode : mode};

            $( print ).printArea( options );
        });

 
    });

  </script>
</body>
</html>