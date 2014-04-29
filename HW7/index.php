<?php
session_start();
include_once("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
}
?>

<?php
function loadPostWall() {
    $getPostSql = "SELECT * FROM MESSAGE WHERE OWNERID='".$_SESSION['ID']."' ORDER BY POSTID DESC";
    $getPostResult = mysql_query($getPostSql);
    echo '<form action="deletePost.php" method="post">';
    while ($posts = mysql_fetch_array($getPostResult)) {
        getMessage($posts);
    }
    echo '</form>';
}
function loadFriendList() {
    echo "Friend list is loading";
}
function getMessage($cur){
    $messageID = $cur['OWNERID'];
    if ($messageID == $_SESSION['ID']) {
        $title = $_SESSION['NICKNAME'];
    } else {
        $title = "Not support friends' posts now";
    }
    $message = htmlspecialchars($cur['MESSAGE']);
    $message = str_replace("\n", "<br/>", $message);
    echo "<table border=\"1\"><tr>";
    echo "<td>".$title."</td>";
    echo '<td><input type="button" name="delete" value="'.$cur['POSTID'].'"></td>';
    echo "<tr><td colspan=\"3\">".$message."</td></tr>";
    echo "</table><br/>";
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
<hr>
Your friend list<br>
<?php
// TODO: Friend list
loadFriendList();
?>
<hr>
<form action="sentPost.php" method="post">
Post:<br><br>
<input type="radio" name="type" value="0" checked>公開<br>
<input type="radio" name="type" value="1">好友可見<br><br>
<textarea name="postStr" cols="50" rows="6" onfocus="this.select()" style="font-size: 16px; overflow:hidden; border:5px double; border-color:#ddccff" placeholder="在這裡輸入訊息～"></textarea><br>
<input type="submit" value="發文">
</form>
<hr>
<?php
// TODO: Post wall
loadPostWall();
?>
</body>
</html>
