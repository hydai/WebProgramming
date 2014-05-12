<?php
session_start();
include("dbInc.php");
?>

<?php
$wantedQueryName = $_POST['friendAccount'];
$wantedQueryNameRep = preg_replace("/[^A-Za-z0-9]/", "", $wantedQueryName);
/*
$findUserSQL = "SELECT ID, ACCOUNT from USER WHERE ACCOUNT = '".$wantedQueryNameRep."'";
$findResult = mysql_query($findUserSQL);
$homeID = 0;
if (mysql_num_rows($findResult) > 0) {
    $friend = mysql_fetch_array($findResult);
    $homeID = $friend['ID'];
} else {
    $homeID = 0;
}
 */
$homeID = 0;
$sql = "SELECT ID from USER WHERE ACCOUNT = ?";
    $mysqli = getMysqli();
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
    
        /* bind parameters for markers */
        $stmt->bind_param("s", $wantedQueryNameRep);
    
        /* execute query */
        $stmt->execute();

        /* bind variables to prepared statement */
        $stmt->bind_result($tmpID);

        /* fetch values */
        if ($stmt->fetch()) {
            $homeID = $tmpID;
        }

        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
$gotoStr = "<meta http-equiv=REFRESH CONTENT=0;url=home.php?id=".$homeID.">";
echo $gotoStr;
?>
