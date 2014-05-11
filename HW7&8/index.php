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

    echo '<tr class="warning"><td id="slaveP">Reply: '.$tmp["NICKNAME"].'</td>';
    echo '<td><button class="btn btn-default" onclick="self.location=\'deletePost.php?rid='.$cur['POSTID'].'\'">Delete</button></td></tr>';
    echo "<tr class='active'><td colspan=\"2\">".$message."</td></tr>";
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
    echo '<div class="panel panel-primary" id="postC">';
    echo '<div class="panel-heading">';
    //echo '<h3 class="panel-title">Master: '.$title.'</h3>';
    echo '</div>';
    echo '<div class="panel-body">';
    echo '<table class="table table-bordered">';
    echo '<tr class="info">';
    echo '<td id="masterP">Master: '.$title.'</td>';
    echo '<td><button class="btn btn-default" onclick="self.location=\'deletePost.php?rid='.$cur['POSTID'].'\'">Delete</button></td></tr>';
    echo "<tr class='active'><td colspan=\"2\">".$message."</td></tr>";
    loadReply($cur['POSTID']);
    echo "<tr><td colspan=\"2\">";
    echo '<form action="replyPost.php" method="post">';
    echo '<textarea class="form-control postTextArea" name="reply" cols="45" rows="3" onfocus="this.select()" placeholder="留言......"></textarea>';
    //echo '</td><td>';
    echo '<input type="hidden" name="master" value="'.$cur['POSTID'].'">';
    echo '<input type="hidden" name="muser" value="'.$_SESSION["ID"].'">';
    echo '<input class="btn btn-default" type="submit" value="送出">';
    echo '</form>';
    echo '</td></tr>';
    echo "</table><br><br>";
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<head>
    <title>Fakebook - Home</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="http://m101.nthu.edu.tw/~s101062124/bootstrap/js/bootstrap.min.js"></script>
    <link href='http://m101.nthu.edu.tw/~s101062124/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
    <!-- use google font API -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700' rel='stylesheet' type='text/css'>
    <link href='main.css' rel='stylesheet' type='text/css'>
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
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Search</button>
      </form>
      <div class="navbar-form navbar-right">
<button class="btn btn-default" onclick="window.location='update.php'"><span class="glyphicon glyphicon-cog"></span> 修改資料</button>
<button class="btn btn-default" onclick="window.location='logout.php'"><span class="glyphicon glyphicon-road"></span> 登出</button>
      </div>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="page-header" id="ph">
<?php
echo "<h1>Hi, user ".$_SESSION['NAME']."(".$_SESSION['NICKNAME'].")";
echo "<br><small>Welcome to fakebook</small></h1>";
?>
</div>

<div class="panel panel-primary" id="postC">
<div class="panel-heading">
        <h3 class="panel-title">Post</h3>
      </div>
      <div class="panel-body">
<form action="sentPost.php" method="post" class="form-horizontal">
<fieldset>
<div class="form-group" id="postIndent">
<label class="control-label" style="font-size:30px">設定權限</label>
    <div class="radio">
     <label>
      <input id="inlineradio1" name="sampleinlineradio" value="0" type="radio">
      公開</label>
    </div>
    <div class="radio">
     <label>
      <input id="inlineradio2" name="sampleinlineradio" value="1" type="radio">
      好友可見</label>
    </div>
<textarea class="form-control postTextArea" name="postStr" cols="50" rows="6" onfocus="this.select()" placeholder="在這裡輸入訊息～"></textarea><br>
<input type="submit" value="發文" class="btn btn-default btn-lg">
</div>
</fieldset>
</form>
      </div>
</div>
</div>
<hr>
<?php
loadPostWall();
?>
</body>
</html>
