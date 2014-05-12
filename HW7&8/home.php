<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
} else {
    $isFriends = false;
}
?>
<?php
$temparray=explode("=",$_SERVER[QUERY_STRING]);
$homeID = (int)$temparray[1];
if ($homeID != 0) {
    $sql = "SELECT NAME, NICKNAME from USER WHERE ID = '$homeID'";
    $results = mysql_query($sql);
    $num = mysql_num_rows($results);
    if ($num >= 1) {
        $result = mysql_fetch_array($results);
        $homeInfo = array (
            "NAME" => $result['NAME'],
            "NICKNAME" => $result['NICKNAME'],
        );
    }
    else {
        $homeID = 0;
    }
}
?>
<?php
function getFile($target) {
    $tmp = "";
    $tmp2 = "";
    $picTypes = array("gif", "jpeg", "jpg", "png");
    $fileTypes = array("txt", "doc", "pdf");
    $getFileInfoSql = "SELECT * FROM IMGMP WHERE MID = '$target'";
    $getFileResult = mysql_query($getFileInfoSql);
    if (mysql_num_rows($getFileResult) > 0) {
        $tmp = mysql_fetch_array($getFileResult);
        $getTypeSql = "SELECT * FROM FILES WHERE PID='".$tmp['PID']."'";
        $getTypeResult = mysql_query($getTypeSql);
        $tmp2 = mysql_fetch_array($getTypeResult);
        $filename = "./fileArea/files/".$tmp['PID'].".".$tmp2['FILETYPE'];
    } else {
        $filename = "./fileArea/default.png";
    }
    if (in_array($tmp2['FILETYPE'], $picTypes)) {
        return "<img src='".$filename."' class='img-rounded postph'>";
    } else {
        return "<a href='$filename'>".$tmp2['FILENAME']."</a>";
    }
}
function getHP($target) {
    $findSql = "SELECT ID FROM USER where ID='$target'";
    $findResult = mysql_query($findSql);
    if (mysql_num_rows($findResult) > 0) {
        $getHPSql = "SELECT * FROM HEADPHOTO where MASTERID='$target'";
        $getResult = mysql_query($getHPSql);
        if (mysql_num_rows($getResult) > 0) {
            $tmp = mysql_fetch_array($getResult);
            $filename = "./fileArea/photos/".$tmp['PID'].".".$tmp['FILETYPE'];
        } else {
            $filename = "./fileArea/default.png";
        }
    } else {
        $filename = "./fileArea/default.png";
    }
    return $filename;
}
    function friendOption($homeID) {
        $tmp = $_SESSION['ID'];
        $getFriendSql = "SELECT * FROM FRIEND WHERE ( MASTER='$tmp' AND SLAVE='$homeID' )";
        $getFriendResult = mysql_query($getFriendSql);
        if ($homeID != $_SESSION['ID']) {
            if (mysql_num_rows($getFriendResult) > 0) {
                echo '<form class="navbar-form navbar-right" action="removeFriend.php" method="post">';
                echo '<div class="form-group">';
                echo '<button class="btn btn-default" type="submit">已加好友，按此鍵刪除</button>';
                echo '<input type="hidden" name="selfID" value="'.$_SESSION['ID'].'">';
                echo '<input type="hidden" name="wantID" value="'.$homeID.'">';
                echo '</div>';
                echo '</form>';
                $isFriends = true;
            } else {
                echo '<form class="navbar-form navbar-right" action="addFriend.php" method="post">';
                echo '<div class="form-group">';
                echo '<button type="submit" class="btn btn-default">加好友</button>';
                echo '<input type="hidden" name="selfID" value="'.$_SESSION['ID'].'">';
                echo '<input type="hidden" name="wantID" value="'.$homeID.'">';
                echo '</div>';
                echo '</form>';
            }
        }
    }
?>
<?php
function loadPostWall($homeID, $homeInfo) {
    $tmp = $_SESSION['ID'];
    $getFriendSql = "SELECT * FROM FRIEND WHERE ( MASTER='$tmp' AND SLAVE='$homeID' )";
    $getFriendResult = mysql_query($getFriendSql);
    if ($homeID != $_SESSION['ID']) {
        if (mysql_num_rows($getFriendResult) > 0) {
            $isFriends = true;
        } else {
            $isFriends = false;
        }
    } else {
        $isFriends = true;
    }
    $getPostSql = "";
    if ($isFriends == true) {
        $getPostSql = "SELECT * FROM MESSAGE WHERE (OWNERID='".$homeID."' AND MASTERID='0') ORDER BY POSTID DESC";
    } else {
        $getPostSql = "SELECT * FROM MESSAGE WHERE (OWNERID='".$homeID."' AND TYPE='0' AND MASTERID='0') ORDER BY POSTID DESC";
    }
    $getPostResult = mysql_query($getPostSql);
    while ($posts = mysql_fetch_array($getPostResult)) {
        getMessage($posts, $homeInfo);
    }
}
function removePost($removeID) {
    $removePost = "DELETE FROM MESSAGE WHERE (POSTID='$removeID' OR MASTERID='$removeID')";
    mysql_query($removePost);
    header("location: index.php");
}
function getReplyMessage($cur) {
    $getUserNicknameSql = "SELECT NICKNAME FROM USER WHERE ID = '".$cur['OWNERID']."'";
    $getUserNicknameResult = mysql_query($getUserNicknameSql);
    $tmp = mysql_fetch_array($getUserNicknameResult);
    $message = htmlspecialchars($cur['MESSAGE']);
    $message = str_replace("\n", "<br/>", $message);

    echo '<tr class="warning"><td id="slaveP"><img src="'.getHP($cur['OWNERID']).'" alt="Head photo" class="img-rounded hp"> Reply: '.$tmp["NICKNAME"].'</td>';
    if ($cur['OWNERID'] != $_SESSION['ID']) {
        echo '<td><button class="btn btn-default" disabled="disabled">Delete</button></td></tr>';
    } else {
        echo '<td><button class="btn btn-default" onclick="self.location=\'deletePost.php?rid='.$cur['POSTID'].'\'">Delete</button></td></tr>';
    }
    $checkPicSql = "SELECT * FROM IMGMP WHERE MID='".$cur['POSTID']."'";
    $checkResult = mysql_query($checkPicSql);
    if (mysql_num_rows($checkResult) > 0) {
        echo "<tr class='active'><td colspan=\"2\">".getFile($cur['POSTID'])." ".$message."</td></tr>";
    } else {
        echo "<tr class='active'><td colspan=\"2\">".$message."</td></tr>";
    }
}
function loadReply($id) {
    $getPostSql = "SELECT * FROM MESSAGE WHERE MASTERID='".$id."'";
    $getPostResult = mysql_query($getPostSql);
    while ($posts = mysql_fetch_array($getPostResult)) {
        getReplyMessage($posts);
    }
}
function getMessage($cur, $homeInfo){
    $messageID = $cur['OWNERID'];
    if ($messageID == $_SESSION['ID']) {
        $title = $_SESSION['NICKNAME'];
    } else {
        $title = $homeInfo['NICKNAME'];
    }
    $message = htmlspecialchars($cur['MESSAGE']);
    $message = str_replace("\n", "<br/>", $message);
    echo '<div class="panel panel-primary" id="postC">';
    echo '<div class="panel-heading">';
    echo '</div>';
    echo '<div class="panel-body">';
    echo '<table class="table table-bordered">';
    echo '<tr class="info">';
    echo '<td id="masterP"><img src="'.getHP($messageID).'" alt="Head photo" class="img-rounded hp"> Master: '.$title.'</td>';
    if ($_SESSION['ID'] != $cur['OWNERID']) {
        echo '<td><button class="btn btn-default" disabled="disabled">Delete</button></td></tr>';
    } else {
        echo '<td><button onclick="self.location=\'deletePost.php?rid='.$cur['POSTID'].'\'">Delete</button></td></tr>';
    }
    $checkPicSql = "SELECT * FROM IMGMP WHERE MID='".$cur['POSTID']."'";
    $checkResult = mysql_query($checkPicSql);
    if (mysql_num_rows($checkResult) > 0) {
        echo "<tr class='active'><td colspan=\"2\">".getFile($cur['POSTID'])." ".$message."</td></tr>";
    } else {
        echo "<tr class='active'><td colspan=\"2\">".$message."</td></tr>";
    }
    loadReply($cur['POSTID']);
    echo "<tr><td colspan=\"2\">";
    echo '<form action="sendReply.php" method="post" enctype="multipart/form-data">';
    echo '<textarea class="form-control postTextArea" name="reply" cols="45" rows="3" onfocus="this.select()" placeholder="留言......"></textarea>';
    //echo '</td><td>';
    echo '<input type="hidden" name="master" value="'.$cur['POSTID'].'">';
    echo '<input type="hidden" name="owner" value="'.$cur['OWNERID'].'">';
    echo '<input type="hidden" name="muser" value="'.$_SESSION["ID"].'">';
    echo '<input id="fileC" type="file" name="file">';
    echo '<button class="btn btn-default" type="submit">送出</button>';
    echo '</form>';
    echo '</td></tr>';
    echo "</table><br><br>";
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
<button class="btn btn-default" onclick="window.location='index.php'"><span class="glyphicon glyphicon-home"></span> 回到首頁</button>
<button class="btn btn-default" onclick="window.location='update.php'"><span class="glyphicon glyphicon-cog"></span> 修改資料</button>
<button class="btn btn-default" onclick="window.location='logout.php'"><span class="glyphicon glyphicon-road"></span> 登出</button>
      </div>
      <?php
if ($homeID != 0) {
    friendOption($homeID);}
?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="page-header" id="ph">
<?php
if ($homeID == 0) {
    echo "<h1>Sorry, nobody is here.</h1>";
}
else {
    echo "<h1>Welcome to ".$homeInfo['NAME']."(".$homeInfo['NICKNAME'].")'s page!</h1>";
}
?>
</div>
<?php
loadPostWall($homeID, $homeInfo);
?>
</body>
</html>
