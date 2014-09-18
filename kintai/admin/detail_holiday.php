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

include_once("mod.admin.php");
include_once("mod.calendar.php");
include_once("ctrl.checklogin.php");
include_once("ctrl.admin.php");
include_once("ctrl.calendar.php");

if (isset($_GET['cid'])) {
    $tmpl = getCalendar($_GET['cid'], $db);
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Kinntai system</title>
    <link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo JS; ?>/jquery.min.js"></script>
</head>
<body>
<div class="displayList">
    <table class="tbl_list">
        <tr>
            <th colspan="3">休日一覧</th>
        </tr>
        <?php
        for ($v = 0; $v < count($tmpl); $v++) {
            echo "<tr>";
            echo "<td>" . $tmpl[$v]['caltp_month'] . "/" . $tmpl[$v]['caltp_day'] . "</td>";
            echo "<td>" . $tmpl[$v]['caltp_year'] . "</td>";
            echo "<td>" . $tmpl[$v]['caltp_holiday'] . "</td>";
            echo "</tr>";
        }
        ?>
        <tr>
            <th colspan="3" height="20">

            </th>
        </tr>
    </table>
</div>
</body>
</html>