<?php
// Generate Salt
function gen_salt()
{
    $rtxt = "abcdefghijklmnopqrstuABCDEFGHIJKLMNOPQRSTU1234567890";
    // Set string to randomize
    $strlength = strlen($rtxt);
    // Get length of the string
    $rs = "";

    for ($i = 0; $i < $strlength; $i++) {
        $rand = round(rand(0, $strlength));
        if ($rand < 0) {
            $rand = 0;
        } else if ($rand > 52) {
            $rand = 52;
        }
        $rs .= $rtxt[$rand];
    }

    $hash = hash("sha512", $rs);
    // Hash the randomly generated string
    return $hash;
}

// Session start
function sec_session_start()
{
    $session_name = 'sec_session_id';
    // Set a custom session name.
    $secure = false;
    //Set to true if using https.
    $httponly = true;
    // This stops javascript being able to access session id.
    ini_set('session.use_only_cookies', 1);
    // Forces sessions to only use cookies.
    $cookieParams = session_get_cookie_params();
    // Gets cookies params.
    session_set_cookie_params($cookieParams['lifetime'], $cookieParams['path'], $cookieParams['domain'], $secure, $httponly);
    session_name($session_name);
    // Sets session name
    session_start();
    // Start session
    session_regenerate_id(true);
    //return true;
}

//Prevent Brute force attack
function checkbrute($user_id, $mysqli)
{
    // Get timestamp of current time
    $now = time();
    // All login attempts are counted from the past 2 hours.
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE usr_id = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $usr_id);
        // Execute the prepared query.
        $stmt->execute();
        $stmt->store_result();
        // If there has been more than 5 failed logins
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

//Login check
function login_check($mysqli)
{
    // Check if all session variables are set
    if (isset($_SESSION['master_id'], $_SESSION['master_login'], $_SESSION['login_string'])) {
        $master_id = $_SESSION['master_id'];
        $login_string = $_SESSION['login_string'];
        $master_login = $_SESSION['master_login'];

        $master_browser = $_SERVER['HTTP_USER_AGENT'];
        // Get the user-agent string of the user.

        if ($stmt = $mysqli->prepare("SELECT master_password FROM master WHERE master_id = ? LIMIT 1")) {
            $stmt->bind_param('i', $master_id);
            // Bind "$user_id" to parameter.
            $stmt->execute();
            // Execute the prepared query.
            $stmt->store_result();

            if ($stmt->num_rows == 1) { // If the user exists
                $stmt->bind_result($master_password);
                // get variables from result.
                $stmt->fetch();
                $login_check = hash('sha512', $master_password . $master_browser);
                if ($login_check == $login_string) {
                    // Logged In!!!!
                    return true;
                } else {
                    // Not logged in
                    return false;
                }
            } else {
                // Not logged in
                return false;
            }
        } else {
            // Not logged in
            return false;
        }
    } else {
        // Not logged in
        return false;
    }
}

// Get Code
function code($id, $mysqli)
{
    $result = $mysqli->query("SELECT code_prefix FROM code where code_id=" . $id . " LIMIT 0,1");
    //$rowcount = mysqli_num_rows($result);
    $rowcount = $result->num_rows;

    if ($rowcount == "") {
        $code_id = 0;
    } else {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $code_id = $row['code_prefix'];
        }
    }
    return $code_id;
}

// AutoGenerate Code
function auto_generate($table, $mysqli)
{
    $code = "";
    $id = "";
    switch ($table) {
        case "customer" :
            $code = code(2, $mysqli);
            $id = "customer_id";
            break;
        case "operator" :
            $code = code(3, $mysqli);
            $id = "operator_id";
            break;
        case "order" :
            $code = code(1, $mysqli);
            $id = "order_id";
            break;
        case "type" :
            $code = code(4, $mysqli);
            $id = "type_id";
    }

    $result = $mysqli->query("SELECT $id FROM `$table` ORDER BY $id DESC limit 1");
    $rowcount = $result->num_rows;

    if ($rowcount == "") {
        $last_id = 0;
    } else {
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            $last_id = $row[0];
        }
    }

    $b = $last_id + 1;
    $b = str_pad($b, 4, '0', STR_PAD_LEFT);
    $code = $code . $b;

    return $code;
}

// Pagination
function paginate($tablename, $limit, $page, $mysqli)
{
    $tablename = $tablename;
    $limit = $limit;
    $stages = $stages;

    if ($page) {
        $start = ($page - 1) * $limit;
    } else {
        $start = 0;
    }

    $query = "SELECT * FROM `$tablename` WHERE $tablename.delete_flag=0";
    $stmt = $mysqli->query($query) or die($mysqli->error);
    $total_pages = $stmt->num_rows;

    if ($page == "") {
        $page = 1;
    }
    $data['i']['limit'] = $limit;
    $data['i']['total_pages'] = $total_pages;
    $data['i']['start'] = $start;

    return $data;
}

function isValid($email)
{
    $isValid = true;
    $atIndex = strrpos($email, "@");
    if (is_bool($atIndex) && !$atIndex) {
        $isValid = false;
    } else {
        $domain = substr($email, $atIndex + 1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64) {
            // local part length exceeded
            $isValid = false;
        } else if ($domainLen < 1 || $domainLen > 255) {
            // domain part length exceeded
            $isValid = false;
        } else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
            // local part starts or ends with '.'
            $isValid = false;
        } else if (preg_match('/\\.\\./', $local)) {
            // local part has two consecutive dots
            $isValid = false;
        } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
            // character not valid in domain part
            $isValid = false;
        } else if (preg_match('/\\.\\./', $domain)) {
            // domain part has two consecutive dots
            $isValid = false;
        } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) {
            // character not valid in local part unless
            // local part is quoted
            if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
                $isValid = false;
            }
        }
        if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
            // domain not found in DNS
            $isValid = false;
        }
    }
    return $isValid;
}

// Convert Point to Valid Time Format
function convertTime($number)
{
    $str_arr = explode('.', $number);

    $num = ($str_arr[0]);
    //floatval
    $point = ($str_arr[1]);
    $count = strlen($str_arr[1]);

    if ($count == 1 && $point < 10) {
        $point = $point * 10;
    }

    while ($point >= 60) {
        $num = $num + 1;
        $point = $point - 60;
    }
    $t = floatval($num . "." . $point);

    return $t;
}

function colorChange($number)
{
    $number = (int)$number;
    $color = ($number <= 0) ? "#F00" : "#000";
    return "<span style='color:" . $color . "'>" . $number . "</span>";
}

// get working time according to user eid
function setTimeZone($gid) {
    switch($gid) {
        case 1:
			date_default_timezone_set("Asia/Tokyo");
            break;

        case 2:
	        date_default_timezone_set("Asia/Shanghai");
            break;

        case 3:
	        date_default_timezone_set("Asia/Ho_Chi_Minh");
            break;

        case 4:
	        date_default_timezone_set("Asia/Rangoon");
            break;
    }
}

//convert to japanese day name
function changejpday($day)
{
    $jpday="";
    switch($day) {
        case "sat":
            $jpday="土";
            break;

        case "sun":
            $jpday="日";
            break;

        case "mon":
            $jpday="月";
            break;

        case "tue":
            $jpday="火";
            break;

        case "wed":
            $jpday="水";
            break;

        case "thu":
            $jpday="木";
            break;

        case "fri":
            $jpday="金";
            break;


    }
    return $jpday;

}

//convert to japanese month name
function changejpmonth($month)
{

    $jpmonth="";
    switch($month) {
        case "jan":
            $jpmonth="1月 JAN";
            break;

        case "feb":
            $jpmonth="2月 FEB";
            break;

        case "mar":
            $jpmonth="3月 MAR";
            break;

        case "apr":
            $jpmonth="4月 APR";
            break;

        case "may":
            $jpmonth="5月 MAY";
            break;

        case "jun":
            $jpmonth="6月 JUN";
            break;

        case "jul":
            $jpmonth="7月 JUL";
            break;

        case "aug":
            $jpmonth="8月 AUG";
            break;

        case "sep":
            $jpmonth="9月  SEP";
            break;

        case "oct":
            $jpmonth="10月  OCT";
            break;

        case "nov":
            $jpmonth="11月  NOV";
            break;

        case "dec":
            $jpmonth="12月  DEC";
            break;
    }
    return $jpmonth;
}