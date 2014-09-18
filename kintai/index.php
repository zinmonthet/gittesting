<?php
/* necessary setting files */
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("ini.dbstring.php");
?>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
        <!-- necessary setting files -->
		<link href="<?php echo CSS; ?>/base.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo CSS; ?>/content.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo CSS; ?>/login.css" rel="stylesheet" type="text/css"/>
		<title>Login</title>
	</head>
	<body>
		<div id="login">
			<form action="controller/ctrl.login.php" method="post" id="form1">
				<?php
				if (isset($_GET["err"]) && $_GET["err"] == 1) {
					echo "<span style='color:red;'>Incorrect username and password!</span>";
				}
				?>
				<h2><img src="<?php echo IMG; ?>/NEW-rubbsoul.png" width="220"></h2>
				<h3>勤怠システム</h3>
				社員ID
				<br>
				<input type="text" id="username" name="username" class="login_field">
				<br>
				パスワード
				<br>
				<input type="password" name="password" id="password" class="login_field"/>
				<br><br>
				<input type="submit" name="button" value="Submit"  class="button"/>
				<br><br>
				When you forgot the password you can reset the password by clicking <a href="password_edit.php">Here</a>.
			</form>
		</div>
	</body>
</html>