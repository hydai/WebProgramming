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
    $getPostSql = "SELECT * FROM MESSAGE WHERE (OWNERID='".$_SESSION['ID']."' AND MASTERID='0') ORDER BY POSTID DESC";
    $getPostResult = mysql_query($getPostSql);
    while ($posts = mysql_fetch_array($getPostResult)) {
        getMessage($posts);
    }
}
function removePost($removeID) {
    $removePost = "DELETE FROM MESSAGE WHERE (POSTID='$removeID' OR MASTERID='$removeID')";
    mysql_query($removePost);
    header("location: index.php");
}
function loadFriendList() {
    echo "Friend list is loading";
}
function getReplyMessage($cur) {
    $getUserNicknameSql = "SELECT NICKNAME FROM USER WHERE ID = '".$cur['OWNERID']."'";
    $getUserNicknameResult = mysql_query($getUserNicknameSql);
    $tmp = mysql_fetch_array($getUserNicknameResult);
    $message = htmlspecialchars($cur['MESSAGE']);
    $message = str_replace("\n", "<br/>", $message);

    echo "<tr><td>Reply: ".$tmp['NICKNAME']."</td>";
    echo '<td><input type="button" value="Delete" onclick="self.location=\'deletePost.php?rid='.$cur['POSTID'].'\'"></td></tr>';
    echo "<tr><td colspan=\"2\">".$message."</td></tr>";
}
function loadReply($id) {
    $getPostSql = "SELECT * FROM MESSAGE WHERE MASTERID='".$id."'";
    $getPostResult = mysql_query($getPostSql);
    while ($posts = mysql_fetch_array($getPostResult)) {
        getReplyMessage($posts);
    }
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
    echo "<td>Master: ".$title."</td>";
    echo '<td><input type="button" value="Delete" onclick="self.location=\'deletePost.php?rid='.$cur['POSTID'].'\'"></td></tr>';
    echo "<tr><td colspan=\"2\">".$message."</td></tr>";
    //TODO: Reply message
    loadReply($cur['POSTID']);
    echo "<tr><td colspan=\"2\">";
    echo '<form action="replyPost.php" method="post">';
    echo '<textarea name="reply" cols="45" rows="3" onfocus="this.select()" style="font-size: 16px; overflow:hidden; border:5px double; border-color:#ddccff" placeholder="留言......"></textarea>';
    //echo '</td><td>';
    echo '<input type="hidden" name="master" value="'.$cur['POSTID'].'">';
    echo '<input type="hidden" name="muser" value="'.$_SESSION["ID"].'">';
    echo '<input type="submit" value="送出">';
    echo '</form>';
    echo '</td></tr>';
    echo "</table><br><br>";
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