<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){ // 如果沒登入過，則直接轉到登入頁面
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
}
else {
    $postStr = $_POST['type'];
    if($_SESSION['ACCOUNT']!=NULL && $_SESSION['ID']==NULL){
        $getUserIDsql = "SELECT ID FROM USER where ACCOUNT = '".$_SESSION['ACCOUNT']."'";
        $getUserIDsqlResult = mysql_query($getUserIDsql);
        $row = mysql_fetch_array($getUserIDsqlResult);
        $_SESSION['ID'] = $row['ID'];
    }
    $ownerID = $_SESSION['ID'];
    $insertPost = "INSERT INTO MESSAGE (OWNERID, TYPE, MESSAGE, MASTERID) VALUES ('$ownerID','$type','$postStr','0')";
    mysql_query($insertPost);
}
?>
