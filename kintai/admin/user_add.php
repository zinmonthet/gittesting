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

include_once("mod.admin.php");
include_once("mod.group.php");
include_once("ctrl.admin.php");
include_once("ctrl.group.php");

$glist = getGroupList($db);

// If the form was submitted, scrub the input (server-side validation)
// see below in the html for the client-side validation using jQuery

$name = '';
$userid = '';
$deptname = '';
$role = '';
$address = '';
$email = '';
$position = '';
$password = '';
$phoneno = '';
$output = '';

if ($_POST) {
	// collect all input and trim to remove leading and trailing whitespaces
	$name = trim($_POST['username']);
	$deptname = trim($_POST['deptname']);
	$role = trim($_POST['role']);
	$branch = trim($_POST['branch']);
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	$errors = array();

	// Validate the input
	if (strlen($name) == 0)
		$name_error = "Please enter your name";

	if (strlen($deptname) == 0) {
		$deptment_errors = "Please enter your department name";
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		$email_error = "Please specify a valid email address";

	if (strlen($password) < 5)
		$password_error = "Passwords must contain at least 5 characters.";
}
?>
<html lang="en">
<head>
	<meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Kinntai system</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
	<script src="<?php echo JS; ?>/jquery.min.js"></script>
	<script src="<?php echo JS; ?>/jquery.validate.min.js"></script>
	<style type="text/css">
		#register-form label.error {
			color: #FF5959;
			font-weight: bold;
			padding-left: 10px;
			font-size: 15px;
		}

        #eid {
            color: #343233;
            font-weight: bold;
            text-decoration: none;
        }
	</style>
</head>
<body>
<?php
include('header_admin.php');
?>
<div class="bd_content">
	<?php include_once('left_menu_admin.php'); ?>
	<div class="dat_content">
		<div class="container">
			<div class="tblCalendarCtn">
				<?php echo $output; ?>

				<table class="tbl_form">
					<tr>
						<th width="50%">ユーザー追加</th>
						<th>ユーザーインポート</th>
					</tr>
					<tr>
						<td>
                            <?php
                            $generate_id = uniqid(rand());
                            $id_generate = substr($generate_id, -6);
                            $id_generate = strtoupper($id_generate);

                            ?>
							<form action="" method="post" id="register-form" novalidate="novalidate">
                                ユーザーID<br/>
                                <label for="" id="eid"><?php echo $id_generate; ?></label>
                                <input type="hidden" id='id' name="id" value="<?php echo $id_generate; ?>"><br/><br>
								ユーザー名<br/>
								<input type="text" id='username' name="username"><br/>
								<?php if ($name_error != "") echo "<span style='color:red;'>" . $name_error . "</span>"; ?>
								<br/>
								部署<br/>
								<input type="text" id="deptname" name="deptname"><br/>
								<?php if ($deptment_errors != "") echo "<span style='color:red;'>" . $deptment_errors . "</span>"; ?>
								<br/>
								レベル<br/>
								<select id='role' name="role" class="required">
									<option value="0">admin</option>
									<option value="1">user</option>
								</select><br/><br/>

								会社ブランチ<br/>
								<select id='branch' name="branch" class="required">
									<option value="1">日本</option>
									<option value="2">中国</option>
									<option value="3">ベトナム</option>
									<option value="4">ミャンマー</option>
								</select><br/><br/>

                                グループ<br/>
								<select id='group' name="group" class="required">
									<?php
									for($g = 0; $g < count($glist); $g++) {
										echo "<option value='". $glist[$g]['group_id'] . "'>" . $glist[$g]['group_name'] . "</option>";
									}
									?>
								</select><br/><br/>

								メール<br/>
								<input type="text" id="email" name="email"><br/>
								<?php if ($email_error != "") echo "<span style='color:red;'>" . $email_error . "</span>"; ?>
								<br/>

								パスワード<br/>
								<input type="password" id="password" name="password"><br/>
								<?php if ($password_error != "") echo "<span style='color:red;'>" . $password_error . "</span>"; ?>

								<br/><br/>
								<input type="submit" value="保存する" name='submit' class="button">　&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="user_list.php"><input type="button" value="戻る" name='submit' class="button"></a>
							</form>
						</td>

						<td>
							<p class="notice">
								※注：インポートするファイルフォーマットサンプルを下記のリンクからダウンロードして確認してください。<br/>
								<a href="<?php echo ROOT; ?>download/importFormatDL/importSampleSheet.xlsx">フォーマットサンプル</a>
							</p>

							<form action="<?php echo CTRL; ?>ctrl.user.php" method="post" enctype="multipart/form-data">
								<input type="file" name="uploadFile"/>
								<input type="hidden" name="cmd" value="import"/>
								<input type="submit" value="保存する" class="button"/>
							</form>
							<?php
							if (isset($_SESSION['cmd']['err']) && $_SESSION['cmd']['err'] == "success") {
								$_SESSION['cmd']['err'] = "";
								echo "<span style='color:red;'>Successfully imported</span>";
								echo $_SESSION['cmd']['err'] = "";
							} elseif ($_SESSION['cmd']['err'] == "error") {
								$_SESSION['cmd']['err'] = "";
								echo "<span style='color:red;'>Please check your file</span>";
							}
							?>
						</td>
					</tr>

				</table>
			</div>
		</div>
	</div>
	<?php include_once("right_menu_admin.php"); ?>
</div>
<!-- jQuery Form Validation code -->
<?php include_once('scripts.php'); ?>
<script>
	$(function () {
		// Setup form validation on the #register-form element
		$("#register-form").validate({
			// Specify the validation rules

			rules: {
				username: "required",
				deptname: "required",
				address: "required",
				role: {
					required: true
				},
				branch: {
					required: true
				},
				email: {
					required: true,
					email: true
				},

				position: "required",
				phoneno: {
					required: true,

					number: true
				}
			},
			// Specify the validation error messages
			messages: {
				username: "Please enter your name",

				deptname: "Please specify your department name",
				address: "Please enter your address",
				role: {
					required: "Please select the role"
				},
				branch: {
					required: "Please select the branch"
				},
				email: "Please enter a valid email address",

				position: "Please enter position",
				phoneno: {
					required: "Please enter phone number"
				}
			},
			submitHandler: function (form) {
				form.submit();
			}
		});
	});
</script>
</body>
</html>