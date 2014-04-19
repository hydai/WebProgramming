<?php
session_start();
include("dbInc.php");
?>
 
<?php
if($_SESSION['id']==null){
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
}
?>
 
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<head></head>
<body bgcolor="#ccccff">
 
<?php
echo "Hi, ".$_SESSION['id']."(".$_SESSION['nickName'].")";
?>
 
<a href="logout.php">登出</a>
 
</body></html>
