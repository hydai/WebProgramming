<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){ // 如果沒登入過，則直接轉到登入後頁面
    echo '<meta http-equiv=REFRESH CONTENT=0;url=index.php>';
} else {
    // Dump errmsg
    session_start();
    $isResetPwd = false;
    $errmsg_arr = array();
    $errflag = false;

    // Check for ext names
    $allowExtName = array("gif", "jpeg", "jpg", "png");
    $allowExts = array("image/git", "image/jpeg", "image/jpg", "image/pjpeg", "image/x-png", "image/png");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);
    $checkType = false;
    $checkSize = false;
    $allowExtLen = count($allowExts);

    // check ext type
    for (int i = 0; i < $allowExtLen; i++) {
        if ($_FILES["file"]["type"] == $allowExts[i]) {
            $checkType = true;
        }
    }
    // check name
    if (!in_array($extension, $allowExtName)) {
        $checkType = false;
    } else {
        $errmsg_arr[] = 'Invalid file extension name';
        $errflag = true;
    }
    // check size
    if ($_FILES["file"]["size"] < 10240000) {
        $checkSize = true;
    } else {
        $errmsg_arr[] = 'The file is too large';
        $errflag = true;
    }
    if ($_FILES["file"]["error"] > 0)
    {
        $errmsg_arr[] = $_FILES["file"]["error"];
        $errflag = true;
    }
    if (!$errflag) {
        // TODO: Write back to db
        $des = $_POST['des'];
        $lng = $_POST['lng'];
        $lat = $_POST['lat'];
        $sql = "INSERT INTO picture (decription, lat, lng) VALUES ('$des', '$lat', '$lng')";
        mysql_query($sql);
        $sql = "SELECT * from picture ORDER BY pictureID DESC LIMIT 1";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $filename = $row['pictureID'];
        move_uploaded_file($_FILES["file"]["tmp_name"],
            "upload/" . $filename);
    } else {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
        session_write_close();
        header("location: upload.php");
        exit();
    }
}
?>
