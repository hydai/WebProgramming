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
$sex = 0;
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
if($email == '') {
    $errmsg_arr[] = 'E-mail cannot be empty.';
    $errflag = true;
}
if (!empty($_FILES['file']['name'])) {
    $allowTypes = array("gif", "jpeg", "jpg", "png");
    $mimetypes = array(
        'gif' => 'image/gif',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'png' => 'image/png'
    );
    $extension = "";
    if ($_FILES["file"]["error"] > 0)
    {
        $errmsg_arr[] = $_FILES["file"]["error"];
        $errflag = true;
    } else {
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);

        // check name
        if (!(in_array($extension, $allowTypes) && ($_FILES["file"]["type"] == $mimetypes[$extension]))) {
            $errmsg_arr[] = 'Invalid file extension name';
            $errflag = true;
        }
        // check size
        if ($_FILES["file"]["size"] > 10240000) {
            $errmsg_arr[] = 'The file is too large (>= 1 1024 Bytes)';
            $errflag = true;
        }
    }
    if ($errflag == false) {
        // Write back to db
        
        $ffname = $_FILES["file"]["name"];
        $sltID = "SELECT PID FROM HEADPHOTO WHERE MASTERID='".$_SESSION["ID"]."'";
        $sltResult = mysql_query($sltID);
        $ffname = preg_replace("/[^A-Za-z0-9]/","",$ffname);
        if (mysql_fetch_row($sltResult) > 0) {
            $addPicSql = "UPDATE HEADPHOTO SET MASTERID='".$_SESSION['ID']."', FILENAME='".$ffname."', FILETYPE='".$extension."'";
        } else {
            $addPicSql = "INSERT INTO HEADPHOTO (MASTERID, FILENAME, FILETYPE) VALUES ('".$_SESSION['ID']."', '".$ffname."', '".$extension."')";
        }
        mysql_query($addPicSql);
        $sltResult = mysql_query($sltID);
        $row = mysql_fetch_array($sltResult);
        $storename = $row['PID'];
        move_uploaded_file($_FILES["file"]["tmp_name"], "fileArea/photos/".$storename.".".$extension);
    }
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
