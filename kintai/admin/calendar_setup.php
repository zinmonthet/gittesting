<?php
include_once("../lib/ini.setting.php");
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
checkLogin("admin", $_SESSION['sess_user_role']);

// get calendarlist
$clist = getCalendarList($db);
?>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Kinntai system</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo CSS; ?>/datepickr.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo JS; ?>/jquery.min.js"></script>
    <script src="<?php echo JS; ?>/datepickr.js"></script>
    <script>
        $(document).ready(function () {
            var MaxInputs = 100; //maximum input boxes allowed
            var InputsWrapper = $("#InputsWrapper"); //Input boxes wrapper ID
            var AddButton = $("#AddMoreFileBox"); //Add button ID

            var x = InputsWrapper.length; //initlal text box count
            var FieldCount = 1; //to keep track of text box added

            $(AddButton).click(function (e)  //on add input button click
            {
                if (x <= MaxInputs) //max input box allowed
                {
                    FieldCount++; //text box added increment
                    //add input box
                    $(InputsWrapper).append('<div class="daylist">' +
                        '<input id="date_pick' + FieldCount + '" type="text" class="time" name="date_pick[]" placeholder="日付け" onmousedown="date_pick(this.id)" required/>' +
                        ' <input type="text" placeholder="休日名" name="date_name[]" id="field_' + FieldCount + '" required/>' +
                        ' <a href="#" class="removeclass button">&minus;</a></div>');
                    x++; //text box increment
                }
                return false;
            });

            $("body").on("click", ".removeclass", function (e) { //user click on remove text
                if (x > 1) {
                    $(this).parent('div').remove(); //remove text box
                    x--; //decrement textbox
                }
                return false;
            })
        });//end of doc ready
    </script>
    <?php echo $rfc; ?>
</head>
<body>
<!--  The form that will be parsed by jQuery before submit  -->
<?php
include('header_admin.php');
?>
<div class="bd_content">
    <?php
    include('left_menu_admin.php');
    ?>
    <div class="dat_content">
        <div class="container">
            <div class="tblCalendarCtn">

                <table class="tbl_form">
                    <tr>
                        <th>カレンダー設定</th>
                    </tr>
                    <tr>
                        <?php if ($clist == "") {
                            echo "<td>カレンダーテンプレートを作ってください。</td>";
                        } else {
                        ?>
                        <!-- calendar setup -->
                        <td>
                            <p class="notice">
                                1月1日の曜日を選択してください。<br/>
                                カレンダーのテンプレートを作成します。　
                            </p>

                            <form action="#" method="post">
                                <select name="day">
                                    <?php
                                    foreach ($daysname as $dnum => $dname) {
                                        echo "<option value='" . $dnum . "'>" . strtoupper($dname) . "</option>";
                                    }
                                    ?>
                                </select>
                                <select name="calendar_name">
                                    <?php
                                    for ($g = 0; $g < count($clist); $g++) {
                                        echo "<option value='" . $clist[$g]['calendar_id'] . "'>" . $clist[$g]['calendar_name'] . "</option>";
                                    }
                                    ?>
                                </select>

                                <div class="daylist">
                                    <input type="submit" class="button" value="保存する" name="formsub">
                                </div>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                    </tr>
                    <tr>
                        <th>カレンダーテンプレート設定</th>
                    </tr>
                    <tr>
                        <td>
                            <p class="notice">
                                カレンダーのテンプレート名を記入して下さい。<br/>
                                ＋ボタンをクリックし、祝日日を作成してください。<br/>
                                日付は(月/日と記入してください。<br/>

                                例:1/1 正月
                            </p>

                            <form action="#" method="post">
                                <div id="InputsWrapper">
                                    <input type="text" id="" name="calendarName" placeholder="カレンダーテンプレート名" required/>
                                    <input type="button" class="button" id="AddMoreFileBox" value="+">

                                    <select name="year">
                                        <?php
                                        for ($i = 0; $i <= LIMITYEARS; $i++) {
                                            echo "<option value='" . (2014 + $i) . "'>" . (2014 + $i) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="daylist">
                                    <input type="submit" class="button" value="保存する" name="date_save">
                                </div>
                            </form>
                        </td>
                    </tr>
                </table>
                <table class="tbl_form">
                    <tr>
                        <th>カレンダーテンプレート一覧</th>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl_time">
                                <?php
                                if (empty($clist)) {
                                    echo "<tr><td>No data</td></tr>";
                                } else {
                                    for ($v = 0; $v < count($clist); $v++) {
                                        echo "<tr>";
                                        echo "<td class='left'>" . $clist[$v]['calendar_name'] . "</td>";
                                        echo "<td><a href='#' url='detail_holiday.php?cid=" . $clist[$v]['calendar_id'] . "' class='popup'>休日詳細を見る</a></td>";
                                        echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?cdel=" . $clist[$v]['calendar_id'] . "'>削除</a></td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th height="20">
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php
    include_once('right_menu_admin.php');
    include_once("scripts.php");
    ?>
</div>
</body>
</html>
