<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){ // 如果沒登入過，則直接轉到登入頁面
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
}
else {
    $removeID = $_GET['rid'];
    /*
    $removePost = "DELETE FROM MESSAGE WHERE (POSTID='$removeID' OR MASTERID='$removeID')";
    mysql_query($removePost);
     */
    $sql = "DELETE FROM MESSAGE WHERE (POSTID=? OR MASTERID=?)";
    $mysqli = getMysqli();
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
    
        /* bind parameters for markers */
        $stmt->bind_param("ii", $removeID, $removeID);
    
        /* execute query */
        $stmt->execute();

        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>

