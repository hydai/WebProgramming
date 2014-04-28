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
<input type="button" onclick="window.location='logout.php'" value="登出">
<input type="button" onclick="window.location='update.php'" value="修改資料">
<hr>
<form action="findFriend.php" method="post">
Search users by account<br>
<input type="text" name="friendAccount" autocomplete="off">
<input type="submit">
</form>
<?php
// TODO: Friend list
?>
<hr>
<form action="sentPost.php" method="post">
<input type="radio" name="type" value="0" checked>公開<br>
<input type="radio" name="type" value="1">好友可見<br>
<textarea cols="50" rows="6" onfocus="this.select()" style="font-size: 16px; overflow:hidden; border:5px double; border-color:#ddccff" placeholder="在這裡輸入訊息～"></textarea>
</form>
</body>
</html>
