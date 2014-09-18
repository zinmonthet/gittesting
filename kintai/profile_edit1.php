<?php
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();

include_once("mod.calendar.php");
include_once("mod.attendance.php");
include_once("mod.admin.php");
include_once("mod.login.php");
include_once("ctrl.checklogin.php");
include_once("ctrl.calendar.php");
include_once("ctrl.attendance.php");
include_once("ctrl.admin.php");

// check user role and authentication
checkSession($_SESSION['sess_user_role']);
checkLogin("user", $_SESSION['sess_user_role']);

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
	$address = trim($_POST['address']);
	$email = trim($_POST['email']);
	$position = trim($_POST['position']);
	$phoneno = trim($_POST['phoneno']);
	$errors = array();

	// Validate the input
	if (strlen($name) == 0)
		array_push($errors, "Please enter your name");

	if (strlen($address) == 0)
		array_push($errors, "Please specify your address");

	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		array_push($errors, "Please specify a valid email address");

	if (strlen($position) == 0)
		array_push($errors, "Please enter your position");

	if (strlen($phoneno) == 0)
		array_push($errors, "Please enter your phone");

	if (count($errors) == 0) {
		array_push($errors, "No Errors! Form Sumbitted Successfully!.. Thanks!");
	}
	//Prepare errors for output
	$output = '';
	foreach ($errors as $val) {
		$output .= "<div class='output'>$val</div>";
	}
}
?>

<html lang="en">
<head>
	<meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Kinntai system</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Form Validation Using jQuery and PHP"/>
	<meta name="keywords" content="jQuery, HTML, PHP, Form Validation, Ajax Form Validation"/>
	<meta name="author" content="DesignHuntR"/>
	<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
	<script src="<?php echo JS; ?>/jquery.min.js"></script>
	<script src="<?php echo JS; ?>/jquery.validate.min.js"></script>
</head>
<body>
<!--  The form that will be parsed by jQuery before submit  -->
<?php
include('header.php');
?>
<div class="bd_content">
	<?php
	include('left_menu.php');
	?>
	<div class="dat_content">
		<div class="container">
			<div class="tblCalendarCtn">
				<form action="#" method="post" id="register-form" novalidate="novalidate">
					<?php foreach ($profile_data as $query_data) { ?>
						<table class="tbl_form">
							<tr>
								<th colspan="2">ユーザー追加</th>
							</tr>
							<tr>
								<td class="right" width="50%">ユーザー名</td>
								<td>
									<input type="text" id='username' name="username"
									       value="<?php echo $query_data['user_name']; ?>">
								</td>
							</tr>
							<tr>
								<td class="right">部門</td>
								<td>
									<input type="text" id="deptname" name="deptname"
									       value="<?php echo $query_data['department']; ?>">
								</td>
							</tr>

							<tr>
								<td class="right">メール</td>
								<td>
									<input type="text" id="email" name="email"
									       value="<?php echo $query_data['email']; ?>">
								</td>
							</tr>
							<tr>
								<td class="right">パスワード変更</td>
								<td>
									<input type="password" id="old_pass" name="old_pass" width=''
									       value="<?php $psw = $query_data['user_password'];
									       $seed = str_split($psw);
									       shuffle($seed);
									       $rand = '';
									       foreach (array_rand($seed, 8) as $k)
										       $rand .= $seed[$k];

									       echo $rand;
									       ?>" disabled>
									<br>
									<input type="password" name='new_pass' placeholder=' Tye new password'
									       id="new_pass">
								</td>
							</tr>
							<tr>
								<td class="right">役割</td>
								<td>
									<input type="text" id="position" name="position"
									       value="<?php echo $query_data['position']; ?>">
								</td>
							</tr>
							<tr>
								<td class="right">形態番号</td>
								<td>
									<input type="text" id="phoneno" name="phoneno"
									       value="<?php echo $query_data['phone']; ?>">
								</td>
							</tr>
							<tr>
								<td class="right">住所</td>
								<td><textarea rows="5" cols="29"
								              name="address"><?php echo $query_data['address']; ?></textarea></td>
							</tr>
							<tr>
								<td class="center" colspan="2">
									<input type="submit" value="Update" name='profile_edit' class="button">
								</td>
							</tr>
						</table>
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
	<?php
	include_once('right_menu.php');
	include_once("scripts.php");
	?>
</div>
<!-- jQuery Form Validation code -->
</body>
</html>