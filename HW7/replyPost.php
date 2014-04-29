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
    header("location: index.php");
}
?>

