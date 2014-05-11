<?php
    session_start();
    include("dbInc.php");
    $target = $_GET['hp'];
    $target = preg_replace("/[^A-Za-z0-9]/","",$target);
    $findSql = "SELECT ID FROM USET where ID='$target'";
    $findResult = mysql_query($findSql);
    if (count($findResult) > 0) {
        $getHPSql = "SELECT * FROM HEADPHOTO where MASTERID='$target'";
        $getResult = mysql_query($getHPSql);
        if (count($getResult) > 0) {
            $tmp = mysql_fetch_array($getResult);
            $filename = "./fileArea/photos/".$tmp['PID'].$tmp['FILETYPE'];
        } else {
            $filename = "./fileArea/default.png";
        }
    } else {
        $filename = "./fileArea/default.png";
    }
    header("Content-type: image/png");
    //ob_end_flush();
    readfile($filename);
?>
