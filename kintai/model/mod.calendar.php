<?php
function insertAYearCalendar($array, $mysqli)
{
    for ($d = 0; $d < count($array); $d++) {
        $query = "INSERT INTO calendar(calendar_month, calendar_year, calendar_date, calendar_events, calendar_status,calendar_day, create_date, delete_flag)";
        $query .= "VALUES('" . $array[$d]['month'] . "','" . $array[$d]['year'] . "','" . $array[$d]['date'] . "','" . addslashes($array[$d]['events']) . "'," . $array[$d]['status'] . ",'" . $array[$d]['day'] . "',NOW(),0)";

        $stmt = $mysqli->prepare($query);

        if ($stmt->execute()) {
            continue;
        }
        return false;
    }
    return true;
}

function insertHoliday($data, $mysqli)
{
    $calendarName = $data['cname'];
    $year = $data['cyear'];

    for ($r = 0; $r < count($data['cdate']); $r++) {
        $date_pick = explode("/", $data['cdate'][$r]);
        $month[$r] = $date_pick[0];
        $day[$r] = $date_pick[1];
    }

    $query = "INSERT INTO calendar_name(calendar_name, calendar_year, create_date, delete_flag) ";
    $query .= "VALUES('" . $calendarName . "', " . $year . ", NOW(), 0)";

    if (!$mysqli->query($query)) {
        return false;
    }

    $insertedID = $mysqli->insert_id;

    for ($r = 0; $r < count($data['cdatename']); $r++) {
        //insert into calendar_tmpl
        $query = "INSERT INTO calendar_tmpl(caltp_id, caltp_year, caltp_month, caltp_day, caltp_holiday, create_date, delete_flag) ";
        $query .= "VALUES(" . $insertedID . ", " . $year . ", " . $month[$r] . ", " . $day[$r] . ", '" . addslashes($data['cdatename'][$r]) . "', NOW(), 0)";

        if (!$mysqli->query($query)) {
            return false;
        }
    }
    return true;
}

function getGroupList($mysqli)
{
    $query = "SELECT * FROM groups";

    if ($stmt = $mysqli->query($query)) {
        if ($stmt->num_rows > 0) {
            while ($result = $stmt->fetch_assoc()) {
                $data[] = $result;
            }
        } else {
            $data = "";
        }
    }
    return $data;
}

function getCalendarList($mysqli)
{
    $query = "SELECT * FROM calendar_name WHERE delete_flag = 0";

    if ($stmt = $mysqli->query($query)) {
        if ($stmt->num_rows > 0) {
            while ($result = $stmt->fetch_assoc()) {
                $data[] = $result;
            }
        } else {
            $data = "";
        }
    }
    return $data;
}

//select from calendar table
function getCalendar($calendar_id, $mysqli)
{
    $query = "SELECT * FROM calendar_tmpl WHERE caltp_id=" . $calendar_id . " AND delete_flag = 0";

    if ($stmt = $mysqli->query($query)) {
        if ($stmt->num_rows > 0) {
            while ($result = $stmt->fetch_assoc()) {
                $data[] = $result;
            }
        } else {
            $data = "";
        }
    }
    return $data;
}

function getCalendarYear($calendar_id, $mysqli)
{
    $query = "SELECT calendar_year FROM calendar_name WHERE calendar_id = $calendar_id";

    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($year);
    $stmt->fetch();

    return $year;
}

// clear calendar data
function clearCalendarData($mysqli)
{
    $query = "DELETE FROM calendar";

    if (!$mysqli->query($query)) {
        return false;
    }
    return true;
}

// delete (delete_flag=1) calendarTemplate
function deleteCalendarTemplate($calendar_id, $mysqli)
{
    $query = "UPDATE calendar_name SET delete_flag = 1 WHERE calendar_id = " . $calendar_id;

    if (!$mysqli->query($query)) {
        return false;
    }
    return true;
}

function calLeapYear($year) {
    return (($year % 4 == 0) || (($year % 100 == 0) && ($year % 400 == 0)));
}