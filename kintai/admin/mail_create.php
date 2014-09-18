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

include_once("mod.mail.php");
include_once("ctrl.mail.php");
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
				<table class="tbl_form">
					<tr>
						<th>
							新メール作成
						</th>
						<th>
							メール編集
						</th>
					</tr>
					<tr>
						<td width="50%">
							<form action="#" method="post">
								メール（TO)<br/>
								<select class="mlp" name="mailTO[]" multiple="multiple">
									<?php
									for ($mt = 0; $mt < count($getMailListAll); $mt++) {
										echo "<option value='" . $getMailListAll[$mt]['user_id'] . "'>" . $getMailListAll[$mt]['user_name'] . " &lt" . $getMailListAll[$mt]['email'] . "&gt" . "</option>";
									}
									?>
								</select><br/><br/>

								メール（CC)<br/>
								<select class="mlp" name="mailCC[]" multiple="multiple">
									<?php
									for ($mt = 0; $mt < count($getMailListAll); $mt++) {
										echo "<option value='" . $getMailListAll[$mt]['user_id'] . "'>" . $getMailListAll[$mt]['user_name'] . " &lt" . $getMailListAll[$mt]['email'] . "&gt" . "</option>";
									}
									?>
								</select><br/><br/>

								メール（BCC）<br/>
								<select class="mlp" name="mailBCC[]" multiple="multiple">
									<?php
									for ($mt = 0; $mt < count($getMailListAll); $mt++) {
										echo "<option value='" . $getMailListAll[$mt]['user_id'] . "'>" . $getMailListAll[$mt]['user_name'] . " &lt" . $getMailListAll[$mt]['email'] . "&gt" . "</option>";
									}
									?>
								</select><br/><br/>
								<input type="hidden" name="newmail" value="true">
								<input type="submit" class="button" value="作成する"/>
							</form>
						</td>
						<td>
							メールテンプレート<br/>
							<?php
							if (empty($getMailList)) {
								echo "メールテンプレートはありません、新しく作ってください。";
							} else {
								echo "<form action='mail_setup.php' method='post'>";
								echo "<select name='maillist'>";
								for ($ml = 0; $ml < count($getMailList); $ml++) {
									if ($getMailList[$ml]['mail_inuse'] == 1) {
										$sel = "selected='selected'";
									}
									echo "<option value='" . $getMailList[$ml]['mail_id'] . "' $sel>" . $getMailList[$ml]['mail_id'] . " &lt" . $getMailList[$ml]['create_date'] . "&gt" . "</option>";
									$sel = "";
								}
								echo "</select><br/><br/>";
								echo "<input type='hidden' name='editmail' value='true' />";
								echo "<input type='submit' class='button' value='編集' /> ";
								echo "<input type='submit' name='use' class='button' value='デフォルトにする' />";
								echo "</form>";
							}
							?>
						</td>
					</tr>
					<tr>
					</tr>
				</table>
			</div>
			<!-- edit -->
		</div>
	</div>
	<?php include_once("right_menu_admin.php"); ?>
</div>
<!-- jQuery Form Validation code -->
<?php include_once('scripts.php'); ?>
</body>
</html>