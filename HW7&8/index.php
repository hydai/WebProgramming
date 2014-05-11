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
    <title>Fakebook - Home</title>
    <script src="http://m101.nthu.edu.tw/~s101062124/bootstrap/js/bootstrap.min.js"></script>
    <link href='http://m101.nthu.edu.tw/~s101062124/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
    <!-- use google font API -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700' rel='stylesheet' type='text/css'>
    <link href='main.css' rel='stylesheet' type='text/css'>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>
<body bgcolor="#ccccff">
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">Fakebook</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <form class="navbar-form navbar-left" role="search" action="findFriend.php" method="post">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search friends" name="friendAccount" autocomplete="off">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li>
<input type="button" class="btn btn-default btn-lg" onclick="window.location='update.php'" value="修改資料">
</li>
        <li>
<input type="button" class="btn btn-default" onclick="window.location='logout.php'" value="登出">
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<?php
echo "Hi, user ".$_SESSION['NAME']."(".$_SESSION['NICKNAME'].")<br>";
echo "Your email: ".$_SESSION['EMAIL']."<br><br>";
?>
<hr>
<div class="panel panel-primary">
<div class="panel-heading">
        <h3 class="panel-title">Post</h3>
      </div>
      <div class="panel-body">
<form action="sentPost.php" method="post">
<input type="radio" name="type" value="0" checked>公開<br>
<input type="radio" name="type" value="1">好友可見<br><br>
<textarea name="postStr" cols="50" rows="6" onfocus="this.select()" style="font-size: 16px; overflow:hidden; border:5px double; border-color:#ddccff" placeholder="在這裡輸入訊息～"></textarea><br>
<input type="submit" value="發文" class="btn btn-default btn-lg">
</form>
      </div>
</div>
<hr>
<?php
// TODO: Post wall
loadPostWall();
?>
</body>
</html>
