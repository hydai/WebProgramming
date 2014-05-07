<?php
session_start();
include("dbInc.php");
?>

<?php
$wantedQueryName = $_POST['friendAccount'];
$wantedQueryNameRep = preg_replace("/[^A-Za-z0-9]/", "", $wantedQueryName);
$findUserSQL = "SELECT ID, ACCOUNT from USER WHERE ACCOUNT = '".$wantedQueryNameRep."'";
$findResult = mysql_query($findUserSQL);
$homeID = 0;
if (mysql_num_rows($findResult) > 0) {
    $friend = mysql_fetch_array($findResult);
    $homeID = $friend['ID'];
} else {
    $homeID = 0;
}
$gotoStr = "<meta http-equiv=REFRESH CONTENT=0;url=home.php?id=".$homeID.">";
echo $gotoStr;
?>
