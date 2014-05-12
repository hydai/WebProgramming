<?php
    session_start();
    include("dbInc.php");
    $target = $_GET['hp'];
    $target = preg_replace("/[^A-Za-z0-9]/","",$target);
    /*
    $findSql = "SELECT ID FROM USET where ID='$target'";
    $findResult = mysql_query($findSql);
    if (count($findResult) > 0) {
        $getHPSql = "SELECT * FROM HEADPHOTO where MASTERID='$target'";
        $getResult = mysql_query($getHPSql);
        if (count($getResult) > 0) {
            $tmp = mysql_fetch_array($getResult);
            $filename = "./fileArea/photos/".$tmp['PID'].$tmp['FILETYPE'];
        } else {
            $filename = "./fileArea/default.png";
        }
    } else {
        $filename = "./fileArea/default.png";
    }
     */
    $filename = "./fileArea/default.png";
    $sql = "SELECT ID FROM USER where ID=?";
    $mysqli = getMysqli();
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
    
        /* bind parameters for markers */
        $stmt->bind_param("i", $target);
    
        /* execute query */
        $stmt->execute();

        /* Store the result (to get properties) */
        $stmt->store_result();

        /* Get the number of rows */
        $num_of_rows = $stmt->num_rows;

        if ($num_of_rows > 0) {
            $sql2 = "SELECT * FROM HEADPHOTO where MASTERID=?";
            $stmt2 = $mysqli->prepare($sql2);
            $stmt2->bind_param("i", $target);
            $stmt2->execute();
            $sql2Result = $stmt2->get_result();
            if (!($row = $sql2Result->fetch_array(MYSQLI_BOTH))) {
                $filename = "./fileArea/photos/".$row['PID'].".".$row['FILETYPE'];
            }
            $stmt2->close();
        }

        /* free results */
        $stmt->free_result();

        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
    header("Content-type: image/png");
    //ob_end_flush();
    readfile($filename);
?>
