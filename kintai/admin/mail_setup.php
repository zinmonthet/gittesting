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

// redirect to new mail create page if mail is empty
if (empty($getMailList)
	|| (!isset($_POST['editmail']) && $_POST['editmail'] != 'true')
	|| (!isset($_POST['maillist']) && empty($_POST['maillist']))) {
	header("location: mail_create.php");
	exit;
}

// get mail list
$getMailTO = explode(":", getMailListTO($_POST['maillist'], $db));
$getMailCC = explode(":", getMailListCC($_POST['maillist'], $db));
$getMailBCC = explode(":", getMailListBCC($_POST['maillist'], $db));
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
				<form action="#" method="post">
					<table class="tbl_form">
						<tr>
							<th colspan="2">メール編集</th>
						</tr>
						<tr>
							<td width="50%">
								メール（TO)<br/>
								<select class="mlp" name="mailTO[]" multiple="multiple">
									<?php
									for ($mt = 0; $mt < count($getMailListAll); $mt++) {
										for($sl = 0; $sl < count($getMailTO); $sl++) {
											if($getMailListAll[$mt]['user_id'] == $getMailTO[$sl]) {
												$sel = "selected='selected'";
												break;
											}
										}

										echo "<option value='" . $getMailListAll[$mt]['user_id'] . "' $sel>" . $getMailListAll[$mt]['user_name'] . " &lt" . $getMailListAll[$mt]['email'] . "&gt" . "</option>";
										$sel = "";
									}
									?>
								</select><br/><br/>

								メール（BCC）<br/>
								<select class="mlp" name="mailBCC[]" multiple="multiple">
									<?php
									for ($mt = 0; $mt < count($getMailListAll); $mt++) {
										for($sl = 0; $sl < count($getMailBCC); $sl++) {
											if($getMailListAll[$mt]['user_id'] == $getMailBCC[$sl]) {
												$sel = "selected='selected'";
												break;
											}
										}

										echo "<option value='" . $getMailListAll[$mt]['user_id'] . "' $sel>" . $getMailListAll[$mt]['user_name'] . " &lt" . $getMailListAll[$mt]['email'] . "&gt" . "</option>";
										$sel = "";
									}
									?>
								</select><br/><br/>
							</td>

							<td>
								メール（CC)<br/>
								<select class="mlp" name="mailCC[]" multiple="multiple">
									<?php
									for ($mt = 0; $mt < count($getMailListAll); $mt++) {
										for($sl = 0; $sl < count($getMailCC); $sl++) {
											if($getMailListAll[$mt]['user_id'] == $getMailCC[$sl]) {
												$sel = "selected='selected'";
												break;
											}
										}

										echo "<option value='" . $getMailListAll[$mt]['user_id'] . "' $sel>" . $getMailListAll[$mt]['user_name'] . " &lt" . $getMailListAll[$mt]['email'] . "&gt" . "</option>";
										$sel = "";
									}
									?>
								</select><br/><br/>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="hidden" name="mailid" value="<?php echo $_POST['maillist']; ?>"/>
								<input type="hidden" name="mailsetup" value="true"/>
								<input type="button" class="button" value="戻る" onclick="javascript:history.back()" />
								<input type="submit" class="button" value="保存"/>
							</td>
						</tr>
					</table>
					<!-- edit -->
				</form>
			</div>
		</div>
	</div>
	<?php include_once("right_menu_admin.php"); ?>
</div>
<!-- jQuery Form Validation code -->
<?php include_once('scripts.php'); ?>
</body>
</html>