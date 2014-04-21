<?php
session_start();
include("dbInc.php");
?>

<?php
session_start();
$isResetPwd = false;
$errmsg_arr = array();
$errflag = false;

// new data
$account = $_SESSION['ACCOUNT'];
$oldpassword = $_POST['oldpassword'];
$password = $_POST['password'];
$passwordRepeat = $_POST['passwordrepeat'];
$name = $_POST['name'];
$nickname = $_POST['nickname'];
$sex = $_POST['sex'];
$email = $_POST['email'];

if($oldpassword != '') {
    if(md5($oldpassword) == $_SESSION['PWD']) {
        $isResetPwd = true;
        if($password == '') {
            $errmsg_arr[] = 'Password cannot be empty.';
            $errflag = true;
        } else if (strlen($password) < 7) {
            $errmsg_arr[] = 'Password is too short.';
            $errflag = true;
        }
        if($passwordRepeat != $password) {
            $errmsg_arr[] = 'Password is different.';
            $errflag = true;
        }
    }
    else {
        $errmsg_arr[] = 'Old Password is incorrect.';
        $errflag = true;
    }
} else {
    $isResetPwd = false;
}
if($name == '') {
    $errmsg_arr[] = 'Your name cannot be empty.';
    $errflag = true;
}
if($sex == '') {
    $errmsg_arr[] = 'Please enter your sex.';
    $errflag = true;
}
if($email == '') {
    $errmsg_arr[] = 'E-mail cannot be empty.';
    $errflag = true;
}

if ($errflag == false) {
    // query
    $password = md5($password);
    if ($isResetPwd == true) {
        $sql = "UPDATE USER SET PWD = '$password'";
        mysql_query($sql);
        $_SESSION['PWD'] = $password;
    }
    $sql = "UPDATE USER SET NAME = '$name', NICKNAME = '$nickname', SEX = '$sex', EMAIL = '$email' WHERE ACCOUNT = '$account'";
    mysql_query($sql);
    // save data to session
    $_SESSION['NAME'] = $name;
    $_SESSION['NICKNAME'] = $nickname;
    $_SESSION['SEX'] = $sex;
    $_SESSION['EMAIL'] = $email;
    header("location: index.php");
} else {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
    header("location: update.php");
    exit();
}

?>
