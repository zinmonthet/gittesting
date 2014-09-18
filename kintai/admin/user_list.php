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

$todaydate = explode("-", date("Y-n-j-D"));
$userid = $_SESSION['sess_user_id'];
$show = true;

include_once("mod.admin.php");
include_once("ctrl.admin.php");
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Kinntai system</title>
	<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
	<script src="<?php echo JS; ?>/jquery.min.js"></script>
</head>
<body>
<?php include_once('header_admin.php'); ?>
<div class="bd_content">
	<?php include('left_menu_admin.php'); ?>
	<div class="dat_content">
		<div class="container">
			<div class="data_tbl">
				<div class="tblCalendarCtn">
					<table class="tbl_str">
						<tr>
							<th class="right">ID</th>
							<th class="left">社員ID</th>
							<th class="left">お名前</th>
							<th class="left">メール</th>
							<th class="left">部署</th>
							<th class="left">グルップ</th>
                            <th class="left">役割</th>
							<th class="left">編集・削除</th>
						</tr>
						<?php for ($r = 0; $r < count($showuser); $r++) {
							$noshow = ($showuser[$r]['delete_flag'] == 1)?"class='noshow'":"";
							?>
							<tr <?php echo $noshow; ?>>
								<td class="right"><?php echo $r + 1; ?></td>
								<td class="left"><?php echo $showuser[$r]["user_eid"]; ?></td>
								<td class="left"><?php echo $showuser[$r]["user_name"]; ?></td>
								<td class="left"><?php echo $showuser[$r]["email"]; ?></td>
								<td class="left"><?php echo $showuser[$r]["department"]; ?></td>
								<td class="left"><?php echo $showuser[$r]["group_name"]; ?></td>
								<td class="left"><?php if($showuser[$r]["user_role"]=='0')
                                                            echo "管理者";else echo "ユーザー" ?></td>
								<td class="left">
									<?php $id = $showuser[$r]["user_id"]; ?>
									<a href="user_edit.php?id=<?php echo $id; ?>">編集 </a>|
									<a href="user_list.php?del_id=<?php echo $id; ?>" onclick="javascript.confirm('Are you sure you want to delete this user?')">削除</a>
								</td>
							</tr>
						<?php } ?>
						<tr>
							<th colspan="8" height="20"></th>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once("right_menu_admin.php");
include_once("scripts.php");
?>
</body>
</html>