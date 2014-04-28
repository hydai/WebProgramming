<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
}
?>

<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<head>
</head>
<body bgcolor="#ccccff">

<?php
echo "Hi, user ".$_SESSION['NAME']."(".$_SESSION['NICKNAME'].")<br>";
echo "Your email: ".$_SESSION['EMAIL']."<br><br>";
?>
<a href="logout.php">登出</a>
<a href="update.php">修改資料</a>
<hr>

</body>
</html>
