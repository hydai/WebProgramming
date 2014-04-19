<?php
session_start();
include("dbInc.php");
?>

<?php
session_start();
$errmsg_arr = array();
$errflag = false;

// new data

$account = $_POST['account'];
$password = $_POST['password'];
$name = $_POST['name'];
$nickname = $_POST['nickname'];
$sex = $_POST['sex'];
$email = $_POST['email'];

if($account == '') {
   $errmsg_arr[] = 'You must enter your account';
   $errflag = true;
}
if($password == '') {
   $errmsg_arr[] = 'You must enter your password';
   $errflag = true;
}
if($name == '') {
   $errmsg_arr[] = 'You must enter your name';
   $errflag = true;
}
if($sex == '') {
   $errmsg_arr[] = 'You must enter your sex';
   $errflag = true;
}
if($email == '') {
   $errmsg_arr[] = 'You must enter your email';
   $errflag = true;
}
if($errflag) {
   $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
   session_write_close();
   header("location: register.php");
   exit();
}
// query
$_SESSION['ACCOUNT'] = $account;
$_SESSION['PWD'] = $password;
$_SESSION['NAME'] = $name;
$_SESSION['NICKNAME'] = $nickname;
$_SESSION['SEX'] = $sex;
$_SESSION['EMAIL'] = $email;
header("location: index.php");

?>
