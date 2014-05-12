<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){ // 如果沒登入過，則直接轉到登入頁面
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
}
else {
    $masterID = $_POST['master'];
    $text = mysql_real_escape_string($_POST["reply"]);
    $ownerID = $_SESSION['ID'];
    $insertPost = "INSERT INTO MESSAGE (OWNERID, TYPE, MESSAGE, MASTERID) VALUES ('$ownerID','0','$text','$masterID')";
    mysql_query($insertPost);
if (!empty($_FILES['file']['name'])) {
    $errflag = false;
    $allowTypes = array("gif", "jpeg", "jpg", "png", "doc", "pdf", "txt");
    $mimetypes = array(
        'gif' => 'image/gif',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'doc' => 'application/msword',
        'pdf' => 'application/pdf',
        'txt' => 'text/plain'
    );
    $extension = "";
    if ($_FILES["file"]["error"] > 0)
    {
        $errflag = true;
    } else {
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);

        // check name
        if (!(in_array($extension, $allowTypes) && ($_FILES["file"]["type"] == $mimetypes[$extension]))) {
            $errflag = true;
        }
        // check size
        if ($_FILES["file"]["size"] > 10240000) {
            $errflag = true;
        }
    }
    if ($errflag == false) {
        // Write back to db
        $ffname = $_FILES["file"]["name"];
        //$ffname = preg_replace("/[^A-Za-z0-9]/","",$ffname);
        $addPicSql = "INSERT INTO FILES (MASTERID, FILENAME, FILETYPE) VALUES ('".$_SESSION['ID']."', '".$ffname."', '".$extension."')";
        mysql_query($addPicSql);
        $sltID = "SELECT PID FROM FILES ORDER BY PID DESC LIMIT 1";
        $sltResult = mysql_query($sltID);
        $row = mysql_fetch_array($sltResult);
        $storename = $row['PID'];
        move_uploaded_file($_FILES["file"]["tmp_name"], "fileArea/files/".$storename.".".$extension);
        $getPostIDSql = "SELECT POSTID FROM MESSAGE ORDER BY POSTID DESC LIMIT 1";
        $gPI = mysql_query($getPostIDSql);
        $gPIR = mysql_fetch_array($gPI);
        $mappingSql = "INSERT INTO IMGMP (MID, PID) VALUES ('".$gPIR['POSTID']."', '".$storename."')";
        mysql_query($mappingSql);
    }
}
    header("location: index.php");
}
?>

