<?php
session_start();
include("dbInc.php");
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
}
?>

<a href="index.php">回到首頁</a>

</body>
</html>
