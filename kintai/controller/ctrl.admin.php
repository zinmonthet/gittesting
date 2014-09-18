<?php
if (isset($_POST['submit'])) {
    $password = $_POST['password'];
    $default_pw=$password;
    $password = hash("sha256", $password);
    define("MAX_LENGTH", 6);
    $intermediateSalt = md5(uniqid(rand(), true));
    $salt = substr($intermediateSalt, 0, MAX_LENGTH);
    $new_pass = hash("sha256", $password . $salt);

    $eid = $_POST['id'];
    $name = $_POST['username'];
    $dep_name = $_POST['deptname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $branch = $_POST['branch'];
    $group = $_POST['group'];

    if ($name != "" && $dep_name != "" && $email != "" && $role != "" && $new_pass != "" && $salt != "") {
        $array['userid'] = $eid;
        $array['name'] = $name;
        $array['dep_name'] = $dep_name;
        $array['email'] = $email;
        $array['role'] = $role;
        $array['new_pass'] = $new_pass;
        $array['salt'] = $salt;
        $array['default_pw'] = $default_pw;
        $array['branch'] = $branch;
        $array['group'] = $group;

        if (insert_data($array, $db)) {
            echo "<script>alert('ユーザーを作成しました。')</script>";
            header("refresh: 0");

            exit;
        } else {
            header("location: " . ROOT . "error.html");
            exit;
        }

    }
}

if ($_SERVER['REQUEST_URI'] == "/kintai/user_list.php") {
    // nothing
} else if (isset($_GET['id'])) {
    $edit_id = $_GET['id'];;
    $user_detail = getdetail($edit_id, $db);
} else if (isset($_GET['del_id'])) {
    $delete_id = $_GET['del_id'];
    if (!delete_user($delete_id, $db)) {
        header("location: " . ROOT . "error.html");
        exit;
    } else {
        //echo "<script>alert('User has been removed.')</script>";
        header("location: ". ROOT . "admin/user_list.php");
        exit;
    }
}

if (isset($_POST['edit'])) {
    $array['username'] = $_POST['username'];
    $array['deptname'] = $_POST['deptname'];
    $array['role'] = $_POST['role'];
    $array['email'] = $_POST['email'];
    $array['u_id'] = $_POST['u_id'];


    if (!update_user($array, $db)) {
        header("location: " . ROOT . "error.html");
        exit;
    } else {
        //echo "<script>alert('Your profile successfully updated')</script>";
        //header("refresh: 0");
        header("location: " . ROOT . "admin/user_list.php");
        exit;
    }
}

// *** User profile edit ***
if (isset($_POST['profile_edit'])) {
    $username = $_POST['username'];
    $deptname = $_POST['deptname'];
    $password = $_POST['new_pass'];
    $default_pw=$password;
    $email = $_POST['email'];
    $password = hash("sha256", $password);
    $intermediateSalt = md5(uniqid(rand(), true));
    $salt = substr($intermediateSalt, 0, MAX_LENGTH);
    $new_pass = hash("sha256", $password . $salt);

    $array['username'] = $username;
    $array['deptname'] = $deptname;
    $array['password'] = $new_pass;
    $array['salt'] = $salt;
    $array['default_pw'] = $default_pw;
    $array['email'] = $email;

    if (!updateprofile($array, $db)) {
        header("location: " . ROOT . "error.html");
        exit;
    } else {
        echo "<script>alert('アップデートしました。')</script>";
        header("refresh: 0");
    }
}

// *** forget password ***
if (isset($_POST['add'])) {
    $user_email = $_POST['email'];
    $_SESSION['sess_user_email'] = $_POST['email'];

    $result = getusermail($user_email, $db);
    foreach ($result as $row) {
        $psw = $row['user_password'];
        $seed = str_split($psw);
        shuffle($seed);
        $rand = '';
        foreach (array_rand($seed, 8) as $k)
            $rand .= $seed[$k];
    }
    $to = $user_email;
    $subject = "Security Code";
    $message = "You can access our site by using the following code:" . "<br>";
    $message .= $rand . "<br>";
    $message .= '<a href="http://atu-japan.co.jp/kintai/password_reset.php?e=' . $_SESSION['sess_user_email'] . '">Click here to change your password</a>';

    ini_set("SMTP", "localhost");
    ini_set("sendmail_from", "info@saj.ir");
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf8' . "\r\n";
    $headers .= 'From: Rubbersoul' . "\r\n";
    if (mail($to, "password reset", $message, $headers)) {
        echo '<script type="text/javascript">';
        echo 'alert("Your security code has been sent to your email!");';
        echo 'window.location.href= "index.php";';
        echo '</script>';
    }
}

if (isset($_POST['savepsw'])) {
    $hidden = $_POST['hidden'];
    $newpsw = $_POST['newpsw'];
    $password = hash("sha256", $newpsw);
    $intermediateSalt = md5(uniqid(rand(), true));
    $salt = substr($intermediateSalt, 0, MAX_LENGTH);
    $new_pass = hash("sha256", $password . $salt);
    if (!update_psw($new_pass, $salt, $hidden, $db)) {
        header("location: " . ROOT . "error.html");
        exit;
    } else {
        echo "<script>alert('Your password successfully updated')</script>";
    }
}

$profile_data = getprofile($_SESSION['sess_user_eid'], $db);
$showuser = getUserList($db, true);