<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){ // 如果沒登入過，則直接轉到登入頁面
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
}
else {
    $masterID = $_POST['master'];
    $ownID = $_POST['owner'];
    $text = mysql_real_escape_string($_POST["reply"]);
    $ownerID = $_SESSION['ID'];
    $mysqli = getMysqli();
    $sql = "INSERT INTO MESSAGE (OWNERID, TYPE, MESSAGE, MASTERID) VALUES (?,'0',?,?)";
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("isi", $ownerID, $text, $masterID);
        /* execute query */
        $stmt->execute();
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
    /*
    $insertPost = "INSERT INTO MESSAGE (OWNERID, TYPE, MESSAGE, MASTERID) VALUES ('$ownerID','0','$text','$masterID')";
    mysql_query($insertPost);
     */
    if (!empty($_FILES['file']['name'])) {
        $errflag = false;
        $allowTypes = array("gif", "jpeg", "jpg", "png", "doc", "pdf", "txt");
        $mimetypes = array(
            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain'
        );
        $extension = "";
        if ($_FILES["file"]["error"] > 0)
        {
            $errflag = true;
        } else {
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);

            // check name
            if (!(in_array($extension, $allowTypes) && ($_FILES["file"]["type"] == $mimetypes[$extension]))) {
                $errflag = true;
            }
            // check size
            if ($_FILES["file"]["size"] > 10240000) {
                $errflag = true;
            }
        }
        if ($errflag == false) {
            // Write back to db
            $ffname = $_FILES["file"]["name"];
            $mysqli = getMysqli();
            $sql = "INSERT INTO FILES (MASTERID, FILENAME, FILETYPE) VALUES (?,?,?)";
            /* create a prepared statement */
            if ($stmt = $mysqli->prepare($sql)) {
                /* bind parameters for markers */
                $stmt->bind_param("iss", $_SESSION['ID'], $ffname, $extension);
                /* execute query */
                $stmt->execute();
                /* close statement */
                $stmt->close();
            }
            closeMysqli($mysqli);
            /*
            $addPicSql = "INSERT INTO FILES (MASTERID, FILENAME, FILETYPE) VALUES ('".$_SESSION['ID']."', '".$ffname."', '".$extension."')";
            mysql_query($addPicSql);
             */
            $storename = -1;
            $mysqli = getMysqli();
            $sql = "SELECT PID FROM FILES ORDER BY PID DESC LIMIT 1";
            /* create a prepared statement */
            if ($stmt = $mysqli->prepare($sql)) {
                /* execute query */
                $stmt->execute();
                $stmt->bind_result($pid);
                while($stmt->fetch()) {
                    $storename = $pid;
                }
                /* close statement */
                $stmt->close();
            }
            closeMysqli($mysqli);
            /*
            $sltID = "SELECT PID FROM FILES ORDER BY PID DESC LIMIT 1";
            $sltResult = mysql_query($sltID);
            $row = mysql_fetch_array($sltResult);
            $storename = $row['PID'];
             */
            move_uploaded_file($_FILES["file"]["tmp_name"], "fileArea/files/".$storename.".".$extension);
            $mysqli = getMysqli();
            $gPIR = "";
            $sql = "SELECT POSTID FROM MESSAGE ORDER BY POSTID DESC LIMIT 1";
            /* create a prepared statement */
            if ($stmt = $mysqli->prepare($sql)) {
                /* execute query */
                $stmt->execute();
                $stmt->bind_result($pid);
                while($stmt->fetch()) {
                    $gPIR = $pid;
                }
                /* close statement */
                $stmt->close();
            }
            closeMysqli($mysqli);
            $mysqli = getMysqli();
            $sql = "INSERT INTO IMGMP (MID, PID) VALUES (?,?)";
            /* create a prepared statement */
            if ($stmt = $mysqli->prepare($sql)) {
                /* execute query */
                $stmt->bind_param("ii", $gPIR, $storename);
                $stmt->execute();
                /* close statement */
                $stmt->close();
            }
            closeMysqli($mysqli);
            /*
            $getPostIDSql = "SELECT POSTID FROM MESSAGE ORDER BY POSTID DESC LIMIT 1";
            $gPI = mysql_query($getPostIDSql);
            $gPIR = mysql_fetch_array($gPI);
            $mappingSql = "INSERT INTO IMGMP (MID, PID) VALUES ('".$gPIR['POSTID']."', '".$storename."')";
            mysql_query($mappingSql);
             */
        }
    }
    header("location: home.php?id=$ownID");
}
?>

