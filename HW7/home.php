<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
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
        if ($homeID != $_SESSION['ID'] && mysql_num_rows($getFriendResult) == 0) {
            echo '<form action="addFriend.php" method="post">';
            echo '<input type="submit" value="加好友">';
            echo '<input type="hidden" name="selfID" value="'.$_SESSION['ID'].'">';
            echo '<input type="hidden" name="wantID" value="'.$homeID.'">';
            echo '</form>';
        }
    }
?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<head>
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

</body>
</html>
