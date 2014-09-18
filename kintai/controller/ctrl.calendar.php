<?php
// Insert calendar
if (isset($_POST["formsub"])) {

    $day = $_POST["day"];

    $calendar_id = $_POST['calendar_name'];

    // get holiday list
    $result = getCalendar($calendar_id, $db);

    // get calendar year
    $year = getCalendarYear($calendar_id, $db);

    // check if given year is leap year or not
    if(calLeapYear($year)) {
        $dayspermonth[1] = 29;
    }

    // build holiday list
    for ($c = 0; $c < count($result); $c++) {
        $mm[] = $result[$c]['caltp_month'] . "." . $result[$c]['caltp_day'] . ":" . $result[$c]['caltp_holiday'];
    }

    // loop month
    for ($m = 1; $m <= $month; $m++) {
        // loop days of the month
        for ($d = 1; $d <= $dayspermonth[$m - 1]; $d++) {

            // add holiday
            $event = "nothing special";
            $status = ($day == 1 || $day == 2) ? "0" : "1"; #determind date is 1:saturday or 2:sunday

            // add special holiday
            for ($e = 0; $e < count($mm); $e++) {
                $holidays = explode(":", $mm[$e]);

                if ($holidays[0] == $m . "." . $d) {
                    $status = 0;
                    $event = $holidays[1];
                    break;
                } else {
                    continue;
                }
            }

            $chunks = array(
                "month" => $m,
                "year" => $year,
                "date" => ($year . "-" . $m . "-" . $d),
                "events" => $event,
                "status" => $status,
                "day" => $daysname[$day]
            );

            // loop 7 days of every week
            if ($day < count($daysname)) {
                $day++;
            } else {
                $day = 1;
            }

            $data[] = $chunks;
        }
    }

    // clear calendar data first
    if(!clearCalendarData($db)) {
        header("location: " . ROOT . "error.html");
        exit;
    }

    // insert new calendar
    if(insertAYearCalendar($data, $db)) {
        $rfc = "<script>alert('Calendar successfully created.')</script>";
    }else {
        header("location: " . ROOT . "error.html");
        exit;
    }
}
if (isset($_POST['date_save'])) {
    $data['cname'] = trim($_POST['calendarName']);
    $data['cdate'] = $_POST['date_pick'];
    $data['cdatename'] = $_POST['date_name'];
    $data['cyear'] = $_POST['year'];

    if($data['cname'] == "") {
        $rfc = "<script>alert('Please insert name of the calendar template.')</script>";
    }

    if(insertHoliday($data, $db)) {
        $rfc = "<script>alert('Template successfully created.')</script>";
    }else {
        header("location: " . ROOT . "error.html");
        exit;
    }
}

if(isset($_GET['cdel'])) {
    $cid = $_GET['cdel'];

    if(deleteCalendarTemplate($cid, $db)) {
       $rfc = $rfc = "<script>alert('Template successfully removed.')</script>";
    }else {
        header("location: ". ROOT . "error.html");
        exit;
    }
}

