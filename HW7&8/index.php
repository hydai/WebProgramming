<?php
session_start();
include_once("dbInc.php");
//include_once("utility.php");

if($_SESSION['ACCOUNT']==null){
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
}
function iloadPostWall() {
    $mysqli = getMysqli();
    $sql = "SELECT * FROM MESSAGE WHERE (OWNERID=? AND MASTERID='0') ORDER BY POSTID DESC";
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("i", $_SESSION['ID']);
        /* execute query */
        $stmt->execute();
        $result = $stmt->get_result();
        while ($posts = $result->fetch_array(MYSQLI_BOTH)) {
            getMessage($posts, $homeInfo);
        }
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
    /*
    $getPostSql = "SELECT * FROM MESSAGE WHERE (OWNERID='".$_SESSION['ID']."' AND MASTERID='0') ORDER BY POSTID DESC";
    $getPostResult = mysql_query($getPostSql);
    while ($posts = mysql_fetch_array($getPostResult)) {
        getMessage($posts);
    }
     */
}
function loadFriendList() {
    echo "Friend list is loading";
}
?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<head>
    <title>Fakebook - Index</title>
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
<form action="sentPost.php" method="post" class="form-horizontal" enctype="multipart/form-data">
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
                        <input id="fileC" type="file" name="file">
    <p class="help-block">圖片支援格式: png, jpg, jpeg, gif(直接顯示于動態)</p>
    <p class="help-block">文件支援格式: txt, doc, pdf</p>
    <p class="help-block">若上傳不支援格式, 會自動移除檔案</p>
<input type="submit" value="發文" class="btn btn-default btn-lg">
</div>
</fieldset>
</form>
      </div>
</div>
</div>
<hr>
<?php
iloadPostWall();
?>
</body>
</html>
