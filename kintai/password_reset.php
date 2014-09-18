<?php
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");
include_once("mod.admin.php");
include_once("ctrl.admin.php");

?>
<html lang="en-US">
<head>
	<meta charset="utf-8">
	<link href="<?php echo CSS; ?>/base.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo CSS; ?>/content.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo CSS; ?>/login.css" rel="stylesheet" type="text/css"/>
	<title>Password edit</title>
</head>
<body>
<?php
if (isset($_GET['e'])) {
	$user_mail = $_GET['e'];
	$result = getusermail($_GET['e'], $db);
	foreach ($result as $row) {
		$psw = $row['user_password'];
		$seed = str_split($psw);
		shuffle($seed);
		$rand = '';
		foreach (array_rand($seed, 8) as $k)
			$rand .= $seed[$k];
	}
}
?>
<div id="psw_reset">
	<form action="#" method="post" id="form">
		<h2 class="p_edit">Password Reset</h2>
		You can change the password<br><br>
		your old password :
		<br>
		<input type="text" id="oldpsw" name="oldpsw" value="" class="login_field">
		<br>
		Tye the new password:
		<br>
		<input type="text" id="newpsw" name="newpsw" class="login_field">
		<br><br>
		<input type="hidden" id="hidden" name="hidden" value="<?php echo $user_mail; ?>">
		<input type="submit" name="savepsw" id="savepsw" value="Submit" class="button"/>

	</form>
</div>
</body>
</html>