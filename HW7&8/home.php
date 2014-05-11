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
    function friendOption($homeID) {
        $tmp = $_SESSION['ID'];
        $getFriendSql = "SELECT * FROM FRIEND WHERE ( MASTER='$tmp' AND SLAVE='$homeID' )";
        $getFriendResult = mysql_query($getFriendSql);
        if ($homeID != $_SESSION['ID']) {
            if (mysql_num_rows($getFriendResult) > 0) {
                echo '<form action="removeFriend.php" method="post">';
                echo '<input type="submit" value="已加好友，按此鍵刪除">';
                echo '<input type="hidden" name="selfID" value="'.$_SESSION['ID'].'">';
                echo '<input type="hidden" name="wantID" value="'.$homeID.'">';
                echo '</form>';
                $isFriends = true;
            } else {
                echo '<form action="addFriend.php" method="post">';
                echo '<input type="submit" value="加好友">';
                echo '<input type="hidden" name="selfID" value="'.$_SESSION['ID'].'">';
                echo '<input type="hidden" name="wantID" value="'.$homeID.'">';
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

    echo "<tr><td>Reply: ".$tmp['NICKNAME']."</td>";
    if ($cur['OWNERID'] != $_SESSION['ID']) {
        echo '<td><input type="button" value="Delete" disabled="disabled"></td></tr>';
    } else {
        echo '<td><input type="button" value="Delete" onclick="self.location=\'deletePost.php?rid='.$cur['POSTID'].'\'"></td></tr>';
    }
    echo "<tr><td colspan=\"2\">".$message."</td></tr>";
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
    echo "<table border=\"1\"><tr>";
    echo "<td>Master: ".$title."</td>";
    if ($_SESSION['ID'] != $cur['OWNERID']) {
        echo '<td><input type="button" value="Delete" disabled="disabled"></td></tr>';
    } else {
        echo '<td><input type="button" value="Delete" onclick="self.location=\'deletePost.php?rid='.$cur['POSTID'].'\'"></td></tr>';
    }
    echo "<tr><td colspan=\"2\">".$message."</td></tr>";
    //TODO: Reply message
    loadReply($cur['POSTID']);
    echo "<tr><td colspan=\"2\">";
    echo '<form action="sendReply.php" method="post">';
    echo '<textarea name="reply" cols="45" rows="3" onfocus="this.select()" style="font-size: 16px; overflow:hidden; border:5px double; border-color:#ddccff" placeholder="留言......"></textarea>';
    //echo '</td><td>';
    echo '<input type="hidden" name="master" value="'.$cur['POSTID'].'">';
    echo '<input type="hidden" name="owner" value="'.$cur['OWNERID'].'">';
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
    <!-- use google font API -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700' rel='stylesheet' type='text/css'>
    <link href='main.css' rel='stylesheet' type='text/css'>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>
<body bgcolor="#ccccff">

<?php
if ($homeID == 0) {
    echo "Sorry, nobody is here.";
}
else {
    echo "Welcome to ".$homeInfo['NAME']."(".$homeInfo['NICKNAME'].")'s page!<br>";
    echo "<hr>";
    friendOption($homeID);
}
?>

<input type="button" onclick="window.location='index.php'" value="回到首頁">

<hr>
<?php
// TODO: Post wall
loadPostWall($homeID, $homeInfo);
?>
</body>
</html>
