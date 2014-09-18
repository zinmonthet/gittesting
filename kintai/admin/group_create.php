<?php
include_once("../lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.login.php");

// check user role and authentication
checkSession($_SESSION['sess_user_role']);
checkLogin("admin", $_SESSION['sess_user_role']);

$todaydate = explode("-", date("Y-n-j"));
$userid = $_SESSION['sess_user_id'];
$show = true;

include_once("mod.group.php");
include_once("ctrl.group.php");

$glist = getGroupList($db);
?>
<html lang="en">
<head>
	<meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Kinntai system</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
	<script src="<?php echo JS; ?>/jquery.min.js"></script>
</head>
<body>
<?php include('header_admin.php'); ?>
<div class="bd_content">
	<?php include_once('left_menu_admin.php'); ?>
	<div class="dat_content">
		<div class="container">
			<div class="tblCalendarCtn">
				<?php echo $output; ?>

				<table class="tbl_form">

					<!-- group create -->
					<tr>
						<th>グループ作成</th>
					</tr>
					<tr>
						<td>
							<form action="#" method="post">
								<table class="tbl_time">
									<tr>
										<td>
											<span class="stt">グループ名</span>
											<span class="stt"><input type="text" name="gname" required/></span>
										</td>
										<td>
											<span class="stt">出勤時間設定</span>
											<span class="stt">
												<input class="timenum hrs" type="text" maxlength="2" name="gin_hrs" value=0 />
													<input type="button" class="button increase increase_hrs" value="+">
													<input type="button" class="button decrease decrease_hrs" value="-">
												<input class="timenum min" type="text" maxlength="2" name="gin_min" value=0 />
													<input type="button" class="button increase increase_min" value="+">
													<input type="button" class="button decrease decrease_min" value="-">
												<input class="timenum sec" type="text" maxlength="2" name="gin_sec" value=0 />
													<input type="button" class="button increase increase_sec" value="+">
													<input type="button" class="button decrease decrease_sec" value="-">
											</span>
										</td>
										<td>
											<span class="stt">退勤時間設定</span>
											<span class="stt">
												<input class="timenum hrs" type="text" maxlength="2" name="gout_hrs" value=0 />
													<input type="button" class="button increase increase_hrs" value="+">
													<input type="button" class="button decrease decrease_hrs" value="-">
												<input class="timenum min" type="text" maxlength="2" name="gout_min" value=0 />
													<input type="button" class="button increase increase_min" value="+">
													<input type="button" class="button decrease decrease_min" value="-">
												<input class="timenum sec" type="text" maxlength="2" name="gout_sec" value=0 />
													<input type="button" class="button increase increase_sec" value="+">
													<input type="button" class="button decrease decrease_sec" value="-">
											</span>
										</td>
									</tr>
									<tr>
										<td colspan="3" class="center">
											<input type="submit" class="button" value="作成"/>
											<input type="hidden" name="gadd" value="true"/>
										</td>
									</tr>
								</table>
							</form>
						</td>
					</tr>

					<!-- group edit -->
					<tr>
						<th>グループ編集</th>
					</tr>
					<tr>
						<td>
							<table class="tbl_time">
								<?php
								if (!isset($glist) && $glist == "") {
									echo "<tr><td>No data</td></tr>";
								} else {
									for ($gl = 0; $gl < count($glist); $gl++) {
										echo "<tr><td class='center'>";
										echo "<form action='#' method='post'>";
										echo "<label class='button3'>" . $glist[$gl]['group_id'] . "</label> ";
										echo "<input type='text' name='gname' value='" . $glist[$gl]['group_name'] . "' placeholder='グルップ名' /> ";
										echo "<input type='text' name='gin' value='" . $glist[$gl]['group_intime'] . "' placeholder='出勤時間' /> ";
										echo "<input type='text' name='gout' value='" . $glist[$gl]['group_outtime'] . "' placeholder='退勤時間' /> ";
										echo "<input type='submit' class='button' value='編集する'>";
										echo "<input type='hidden' name='gid' value='" . $glist[$gl]['group_id'] . "'>";
										echo "<input type='hidden' name='gedit' value='true'/>";
										echo "</form>";
										echo "</td></tr>";
									}
								}
								?>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<?php
include_once("right_menu_admin.php");
include_once('scripts.php');
?>
</body>
</html>