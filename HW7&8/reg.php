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
$passwordRepeat = $_POST['passwordrepeat'];
$name = $_POST['name'];
$nickname = $_POST['nickname'];
$sex = 0;
$email = $_POST['email'];

$accRep = preg_replace("/[A-Za-z0-9]/","",$account);
$account = preg_replace("/[^A-Za-z0-9]/", "", $account);

$mysqli = getMysqli();
$sql = "SELECT * from USER WHERE ACCOUNT = ?";
/* create a prepared statement */
if ($stmt = $mysqli->prepare($sql)) {
    /* bind parameters for markers */
    $stmt->bind_param("s", $account);
    /* execute query */
    $stmt->execute();
    $result = $stmt->get_result();
    while ($tmp = $result->fetch_array(MYSQLI_BOTH)) {
        $errmsg_arr[] = 'Account has been used.';
        $errflag = true;
        break;
    }
    /* close statement */
    $stmt->close();
}
closeMysqli($mysqli);
/*
$test_username_sql = "SELECT * from USER WHERE ACCOUNT = '".$account."'";
$test_username_result = mysql_query($test_username_sql);
while ($tmp = mysql_fetch_array($test_username_result)) {
    $errmsg_arr[] = 'Account has been used.';
    $errflag = true;
    break;
}
 */
if($account == '') {
    $errmsg_arr[] = 'Account cannot be empty.';
    $errflag = true;
} else if ($accRep != '') {
    $errmsg_arr[] = 'Account is invalid. Please use A-Z, 0-9, a-z.';
    $errflag = true;
} else if (strlen($account) < 6) {
    $errmsg_arr[] = 'Account is too short.';
    $errflag = true;
}
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
if($name == '') {
    $errmsg_arr[] = 'Your name cannot be empty.';
    $errflag = true;
}
if($email == '') {
    $errmsg_arr[] = 'E-mail cannot be empty.';
    $errflag = true;
}

if ($errflag == false) {
    // query
    $password = md5($password);
    $mysqli = getMysqli();
    $sql = "INSERT INTO USER (ACCOUNT, PWD, NAME, NICKNAME, SEX, EMAIL) VALUES (?,?,?,?,?,?)";
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("ssssss", $account, $password, $name, $nickname, $sex, $email);
        /* execute query */
        $stmt->execute();
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
    /*
    $sql = "INSERT INTO USER (ID, ACCOUNT, PWD, NAME, NICKNAME, SEX, EMAIL) VALUES ('','$account','$password','$name','$nickname','$sex','$email')";
    mysql_query($sql);
     */
    // save data to session
    $_SESSION['ACCOUNT'] = $account;
    $_SESSION['PWD'] = $password;
    $_SESSION['NAME'] = $name;
    $_SESSION['NICKNAME'] = $nickname;
    $_SESSION['SEX'] = $sex;
    $_SESSION['EMAIL'] = $email;
    header("location: index.php");
} else {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
    header("location: login.php");
    exit();
}

?>
