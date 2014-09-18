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
include_once("mod.attendance.php");
include_once("ctrl.checklogin.php");
include_once("ctrl.admin.php");
include_once("ctrl.attendance.php");
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Kinntai system</title>
    <link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo JS; ?>/jquery.min.js"></script>
</head>
<body>
<?php include('header_admin.php'); ?>
<div class="bd_content">
    <?php include('left_menu_admin.php'); ?>
    <div class="dat_content">
        <div class="container">
            <div class="search_bar">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<span id='filter'>
								<select class="button2" name="year">
                                    <?php
                                    for ($i = 0; $i <= LIMITYEARS; $i++) {
                                        // re-select
                                        if ((2014 + $i) == $my[0]) {
                                            $selected = "selected='selected'";
                                        } else {
                                            $selected = "";
                                        }
                                        echo "<option value='" . (2014 + $i) . "' $selected>" . (2014 + $i) . "</option>";
                                        $selected = "";
                                    }
                                    ?>
                                </select> 年
                                <?php echo $m; ?>
                                <select class="button2" name="month">
                                    <?php
                                    for ($i = 1; $i <= count($ymd); $i++) {
                                        // re-select
                                        if ($ymd[$i - 1]['mindex'] == $my[1]) {
                                            $selected = "selected='selected'";
                                        } else {
                                            $selected = "";
                                        }
                                        echo "<option value='" . $ymd[$i - 1]['mindex'] . "' $selected>" . changejpmonth($ymd[$i - 1]['mnameshort']) . "</option>";
                                        $selected = "";
                                    }
                                    ?>
                                </select> 月
								<input type="hidden" name="filterAdmin" value="true">
								<input class="button" type="submit" value="検索" name="btn_search" id="btn_search">
							</span>
                </form>
            </div>

            <div class="data_tbl">
                <?php
                if ($show) {
                    if (isset($my) && $my != "" && !empty($my)) {
                        echo '<div class="tblCalendarCtn">';
                        echo '<table class="tbl_str">';
                        echo '<tr>';
                        echo '<th align="left">ID</th>';
                        echo '<th align="left">社員ID</th>';
                        echo '<th align="left">ユーザー名</th>';
                        echo '<th align="left">グルップ</th>';
                        echo '<th align="left">メール</th>';
                        echo '<th align="left">詳細</th>';
                        echo '</tr>';

                        $userlist = getUserList($db);
                        $d = $my[0] . "-" . $my[1] . "-" . $my[2];

                        if (empty($userlist)) {
                            echo "<tr><td colspan='6'>No data</td></tr>";
                        } else {
                            for ($u = 0; $u < count($userlist); $u++) {
                                echo "<tr>";
                                echo "<td>" . $userlist[$u]['user_id'] . "</td>";
                                echo "<td>" . $userlist[$u]['user_eid'] . "</td>";
                                echo "<td>" . $userlist[$u]['user_name'] . "</td>";
                                echo "<td>" . $userlist[$u]['group_name'] . "</td>";
                                echo "<td>" . $userlist[$u]['email'] . "</td>";
                                echo "<td><a href='#' url='detail.php?uid=" . $userlist[$u]['user_id'] . "&cdate=" . $d . "' class='popup'>詳細</a></td>";
                                echo "</tr>";
                            }
                        }
                        echo '<tr>';
                        echo '<th colspan="9" height="20"></th>';
                        echo '</tr>';
                        echo '</table>';
                        echo '</div>';
                    }
                } else {
                    echo "<div class='info'><span class='nodata'>No data</span></div>";;
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    include_once("right_menu_admin.php");
    include_once("scripts.php");
    ?>
</div>
</body>
</html>