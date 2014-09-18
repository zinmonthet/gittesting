<?php
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();
setTimeZone($_SESSION['sess_user_bid']);

include_once("mod.select.php");
include_once("mod.attendance.php");
include_once("mod.login.php");
include_once("ctrl.attendance.php");
include_once("ctrl.checklogin.php");

// check user role and authentication
checkSession($_SESSION['sess_user_role']);
checkLogin("user", $_SESSION['sess_user_role']);

$userid = (!isset($_GET['userid']) || $_GET['userid'] == "") ? 1 : $_GET['userid'];
$todaydate = explode("-", date("Y-n-j"));

$currenttime = date("H:i:s");
$currentdate = date("Y-n-j");
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Kinntai system</title>
	<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo CSS; ?>/datepickr.css" rel="stylesheet" type="text/css"/>
	<script src="<?php echo JS; ?>/jquery.min.js"></script>
	<script src="<?php echo JS; ?>/datepickr.js"></script>
	<script>
		$(document).ready(function () {
			new datepickr('scrollDefaultExample', {
				dateFormat: 'Y-m-d'
			});
		});
	</script>
	<?php echo $rfc; ?>
</head>
<body>

<?php
include('header.php');
?>
<div class="bd_content">
	<?php include('left_menu.php'); ?>
	<div class="dat_content">
		<div class="container" style="text-align:center;">
			<div class="tblCalendarCtn">
				<table class="tbl_form">
					<tr>
						<th>手記入</th>
					</tr>
					<tr>
						<td>
							<p class="notice">
								※夜24時以降、残業した場合は下記の通り記入してください。<br/>
                                例）AM 1時 ⇒ 25:00　AM 2時 ⇒26:00
							</p>

							<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
								退勤時間：
								<!--<input type="text" id="txt_time" name="txt_time" placeholder="時間">-->

								時 <input class="timenum hrs" type="text" maxlength="2" name="gout_hrs" value=0 />
								<input type="button" class="button increase increase_hrs" value="+">
								<input type="button" class="button decrease decrease_hrs" value="-">
								分 <input class="timenum min" type="text" maxlength="2" name="gout_min" value=0 />
								<input type="button" class="button increase increase_min" value="+">
								<input type="button" class="button decrease decrease_min" value="-">
								秒 <input class="timenum sec" type="text" maxlength="2" name="gout_sec" value=0 />
								<input type="button" class="button increase increase_sec" value="+">
								<input type="button" class="button decrease decrease_sec" value="-">

								<input id="scrollDefaultExample" type="text" class="time" name="gout_date" placeholder="日付け"/>
								<input type="submit" class="button" value="退勤する" id="sub_leave" name="sub_leave">

								<br/>
								<br/>
								<textarea placeholder="コメント" name="attd_comment" style="width:100%;height:200px;"></textarea>
							</form>
						</td>
					</tr>
					<tr>
						<th>退勤</th>
					</tr>
					<tr>
						<td align="center">
							<span style='font-size:1200% !important;line-height:100%;'
							      class="timectn"><?php echo $currenttime; ?></span><br/>

							<h2>コメント</h2>

							<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
								<textarea name="attd_comment" style="width:100%;height:200px;"></textarea>

								<input type="hidden" name="attd_out_time" class="hid"
								       value="<?php echo $currenttime; ?>"/>
								<input type="hidden" name="attd_date" value="<?php echo $currentdate; ?>"/>
								<input type="hidden" name="userid" value="<?php echo $_SESSION['sess_user_id']; ?>">
								<input type="hidden" name="form_submit_out" value="true"/>
								<input class="button" style="margin-top:10px;" type="submit" value="退勤する">
							</form>
						</td>
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