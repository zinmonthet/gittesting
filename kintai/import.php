<?php
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("ini.functions.php");
include_once ("ctrl.checklogin.php");

$userid = (!isset($_GET['userid']) || $_GET['userid'] == "") ? 1 : $_GET['userid'];
$todaydate = explode("-", date("Y-n-j"));
$show = true;

include_once ("mod.calendar.php");
include_once ("mod.attendance.php");
include_once ("ctrl.checklogin.php");
include_once ("ctrl.calendar.php");
include_once ("ctrl.attendance.php");

sec_session_start();
if(!$_SESSION['sess_user_id']){
header('Location: index.php');
}

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Kinntai system</title>
		<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo JS; ?>/jquery.js"></script>
	</head>
	<body>

		<?php
		include ('header.php');
		?>

		<div class="bd_content">
			<?php
			include ('left_menu.php');
			?>
			<div class="dat_content">

				<div class="container">
					<p class="hdr">取り込み</p>
				<form action="<?php echo CTRL; ?>ctrl.user.php" method="post" enctype="multipart/form-data">
					<input type="file" name="uploadFile" />
					<input type="submit" value="Import" class="btn-mod" />
                    <input type="hidden" name="cmd" value="import" />

				</form>
				
			</div>
		</div>
	</body>
</html>
