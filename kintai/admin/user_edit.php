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
include_once("mod.group.php");
include_once("ctrl.admin.php");
include_once("ctrl.group.php");

// get group list
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
	//  $userid = trim($_POST['userid']);
	$deptname = trim($_POST['deptname']);
	$role = trim($_POST['role']);
	$email = trim($_POST['email']);

	$errors = array();

	// Validate the input
	if (strlen($name) == 0)
		array_push($errors, "Please enter your name");

	if (strlen($deptname) == 0)
		array_push($errors, "Please enter your ID");
	if (strlen($role) == 0)
		array_push($errors, "Please enter your ID");

	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		array_push($errors, "Please specify a valid email address");


	// If no errors were found, proceed with storing the user input
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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Form Validation Using jQuery and PHP"/>
	<meta name="keywords" content="jQuery, HTML, PHP, Form Validation, Ajax Form Validation"/>
	<meta name="author" content="DesignHuntR"/>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
	<title>Kinntai system</title>
	<script src="<?php echo JS; ?>/jquery.min.js"></script>
	<script src="<?php echo JS; ?>/jquery.validate.min.js"></script>
</head>
<body>
<?php include('header_admin.php'); ?>
<div class="bd_content">
	<?php include_once('left_menu_admin.php'); ?>
	<div class="dat_content">
		<div class="container">
			<form action="#" method="post" id="edit-form" novalidate="novalidate">
				<?php foreach ($user_detail as $query_data) { ?>
					<table cellpadding="5px" width="900px">
                        <tr>
                            <td width="200px">社員ID</td>
                            <td>

                                <input type="text" id='user_eid' name="user_eid" value="<?php echo $query_data['user_eid']; ?>" disabled="disabled">

                            </td>
                        </tr>
						<tr>
							<td width="200px">ユーザー名</td>
							<td>
								<input type="text" id='username' name="username" value="<?php echo $query_data['user_name']; ?>">
							</td>
						</tr>
						<tr>
							<td>部署</td>
							<td>
								<input type="text" id="deptname" name="deptname" value="<?php echo $query_data['department']; ?>">
							</td>
						</tr>
						<tr>
							<td>レベル</td>
							<td>
								<select style="width:250px;" id='role' name="role">
									<?php $role = $query_data['user_role'];
									if ($role == 0) {
										echo '<option value="0">admin</option>';
										echo '<option value="1">user</option>';
									} else {
										echo '<option value="1">user</option>';
										echo '<option value="0">admin</option>';
									}
									?>
								</select>
							</td>
						</tr>
                        <tr>
                            <td>パスワード</td>
                            <td>
                                <input type="text" id="deptname" name="deptname" value="<?php echo $query_data['default_pw']; ?>"  disabled="disabled">
                            </td>
                        </tr>
						<tr>
							<td>グループ
							</td>
							<td>


							<!--	<select name="group">-->
									<?php
									for($g = 0; $g < count($glist); $g++) {
										if($query_data['group_id'] == $glist[$g]['group_id']) {?>
                                            <input type="text" id="group_name" name="group_name" value="<?php echo $glist[$g]['group_name']; ?>" disabled="disabled">


									<?php 	}
										//echo "<option value='". $glist[$g]['group_id'] . "' $selected>" . $glist[$g]['group_name'] . "</option>";
										//$selected = "";
									}
									?>
							<!--	</select>-->
							</td>
						</tr>
						<tr>
							<td>メール</td>
							<td>
								<input type="text" id="email" name="email" value="<?php echo $query_data['email']; ?>">
							</td>
						</tr>
						<tr>
							<td>
								<input type="hidden" id="u_id" name="u_id" value="<?php echo $query_data['user_id']; ?>">
							</td>
							<td>
								<input type="submit" value="保存する" id="edit" name="edit" class="btn_type">
							</td>
						</tr>
					</table>
				<?php } ?>
			</form>
		</div>
	</div>
	<?php
	include_once("right_menu_admin.php");
	include_once("scripts.php");
	?>
</div>
</body>
</html>
