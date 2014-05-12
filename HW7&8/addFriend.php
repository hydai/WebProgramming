<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){ // 如果沒登入過，則直接轉到登入頁面
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
}
else {
    $selfID = $_POST['selfID'];
    $wantID = $_POST['wantID'];
    /*
    $insertFriend = "INSERT INTO FRIEND (MASTER, SLAVE) VALUES ('$selfID', '$wantID')";
    mysql_query($insertFriend);
     */

    $sql = "INSERT INTO FRIEND (MASTER, SLAVE) VALUES (?,?)";
    $mysqli = getMysqli();
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
    
        /* bind parameters for markers */
        $stmt->bind_param("ii", $selfID, $wantID);
    
        /* execute query */
        $stmt->execute();

        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
    header("location: home.php?id=$wantID");
}
?>

