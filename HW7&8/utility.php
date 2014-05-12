<?php
session_start();
include_once("dbInc.php");
function getFile($target) {
    $tmp = "";
    $tmp2 = "";
    $tfilename = "";
    $picTypes = array("gif", "jpeg", "jpg", "png");
    $fileTypes = array("txt", "doc", "pdf");
    $mysqli = getMysqli();
    $sql = "SELECT * FROM IMGMP WHERE MID = ?";
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("i", $target);
        /* execute query */
        $stmt->execute();
        $result = $stmt->get_result();
        if (!($tmp = $result->fetch_array(MYSQLI_BOTH))) {
            $sql2 = "SELECT FILETYPE, FILENAME FROM FILES WHERE PID=?";
            if ($stmt2 = $mysqli->prepare($sql2)) {
                $stmt2->bind_param("i", $tmp['PID']);
                $stmt2->execute();
                $stmt2->bind_result($tmp2, $tfilename);
                if ($stmt2->fetch()) {
                    $filename = "./fileArea/files/".$tmp['PID'].".".$tmp2;
                }
            }
            $stmt2->close();
        } else {
            $filename = "./fileArea/default.png";
        }
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
    if (in_array($tmp2, $picTypes)) {
        return "<img src='".$filename."' class='img-rounded postph'>";
    } else {
        return "<a href='$filename'>".$tfilename."</a>";
    }
}
function getHP($target) {
    $mysqli = getMysqli();
    $filename = "./fileArea/default.png";
    $sql = "SELECT ID FROM USER where ID=?";
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("i", $target);
        /* execute query */
        $stmt->execute();
        $result = $stmt->get_result();
        if (!($tmp = $result->fetch_array(MYSQLI_BOTH))) {
            $sql2 = "SELECT * FROM HEADPHOTO where MASTERID=?";
            if ($stmt2 = $mysqli->prepare($sql2)) {
                $stmt2->bind_param("i", $target);
                $stmt2->execute();
                $stmt2->bind_result($tmp2, $tfilename);
                if ($stmt2->fetch()) {
                    $filename = "./fileArea/photos/".$tmp['PID'].".".$tmp2;
                }
            }
            $stmt2->close();
        } else {
            $filename = "./fileArea/default.png";
        }
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
    return $filename;
}
function friendOption($homeID) {
    $tmp = $_SESSION['ID'];
    $mysqli = getMysqli();
    $sql = "SELECT * FROM FRIEND WHERE ( MASTER=? AND SLAVE=? )";
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("ii", $tmp, $homeID);
        /* execute query */
        $stmt->execute();
        $result = $stmt->get_result();
        $num_of_rows = $result->num_rows;
        if ($homeID != $_SESSION['ID']) {
            if ($num_of_rows > 0) {
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
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
}
function friendOption($homeID) {
    $tmp = $_SESSION['ID'];
    $mysqli = getMysqli();
    $sql = "SELECT * FROM FRIEND WHERE ( MASTER=? AND SLAVE=? )";
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("ii", $tmp, $homeID);
        /* execute query */
        $stmt->execute();
        $result = $stmt->get_result();
        $num_of_rows = $result->num_rows;
        if ($homeID != $_SESSION['ID']) {
            if ($num_of_rows > 0) {
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
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
}
function loadPostWall($homeID, $homeInfo) {
    $tmp = $_SESSION['ID'];
    $mysqli = getMysqli();
    $sql = "SELECT * FROM FRIEND WHERE ( MASTER=? AND SLAVE=? )";
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("ii", $tmp, $homeID);
        /* execute query */
        $stmt->execute();
        $result = $stmt->get_result();
        $num_of_rows = $result->num_rows;
        if ($homeID != $_SESSION['ID']) {
            if ($num_of_rows > 0) {
                $isFriends = true;
            } else {
                $isFriends = false;
            }
        } else {
            $isFriends = true;
        }
        /* close statement */
        $stmt->close();
    }
    $sql = "";
    if ($isFriends == true) {
        $sql = "SELECT * FROM MESSAGE WHERE (OWNERID=? AND MASTERID='0') ORDER BY POSTID DESC";
    } else {
        $sql = "SELECT * FROM MESSAGE WHERE (OWNERID=? AND TYPE='0' AND MASTERID='0') ORDER BY POSTID DESC";
    }
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("i", $homeID);
        /* execute query */
        $stmt->execute();
        $result = $stmt->get_result();
        while ($posts = $result->fetch_array(MYSQLI_BOTH)) {
            getMessage($posts, $homeInfo);
        }
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);

}
function removePost($removeID) {
    $mysqli = getMysqli();
    $sql = "DELETE FROM MESSAGE WHERE (POSTID=? OR MASTERID=?)";
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
    header("location: index.php");
}
function getReplyMessage($cur) {
    $tmp = "";
    $sql = "SELECT NICKNAME FROM USER WHERE ID = ?";
    $mysqli = getMysqli();
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("i", $cur['OWNERID']);
        /* execute query */
        $stmt->execute();
        $result = $stmt->get_result();
        $tmp = $result->fetch_array(MYSQLI_BOTH);
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
    $message = htmlspecialchars($cur['MESSAGE']);
    $message = str_replace("\n", "<br/>", $message);

    echo '<tr class="warning"><td id="slaveP"><img src="'.getHP($cur['OWNERID']).'" alt="Head photo" class="img-rounded hp"> Reply: '.$tmp["NICKNAME"].'</td>';
    if ($cur['OWNERID'] != $_SESSION['ID']) {
        echo '<td><button class="btn btn-default" disabled="disabled">Delete</button></td></tr>';
    } else {
        echo '<td><button class="btn btn-default" onclick="self.location=\'deletePost.php?rid='.$cur['POSTID'].'\'">Delete</button></td></tr>';
    }
    $sql = "SELECT * FROM IMGMP WHERE MID=?";
    $mysqli = getMysqli();
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("i", $cur['POSTID']);
        /* execute query */
        $stmt->execute();
        $result = $stmt->get_result();
        $num_of_rows = $result->num_rows;
        if ($num_of_rows > 0) {
            echo "<tr class='active'><td colspan=\"2\">".getFile($cur['POSTID'])." ".$message."</td></tr>";
        } else {
            echo "<tr class='active'><td colspan=\"2\">".$message."</td></tr>";
        }
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
}
function loadMsg($id, $message) {
    $sql = "SELECT * FROM IMGMP WHERE MID=?";
    $mysqli = getMysqli();
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("i", $id);
        /* execute query */
        $stmt->execute();
        /* Store the result (to get properties) */
        $stmt->store_result();
        /* Get the number of rows */
        $num_of_rows = $stmt->num_rows;
        if ($num_of_rows > 0) {
            echo "<tr class='active'><td colspan=\"2\">".getFile($id)." ".$message."</td></tr>";
        } else {
            echo "<tr class='active'><td colspan=\"2\">".$message."</td></tr>";
        }
        /* free results */
        $stmt->free_result();
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
}
function loadReply($id) {
    $mysqli = getMysqli();
    $sql = "SELECT * FROM MESSAGE WHERE MASTERID=?";
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
        /* bind parameters for markers */
        $stmt->bind_param("i", $id);
        /* execute query */
        $stmt->execute();
        $result = $stmt->get_result();
        while ($posts = $result->fetch_array(MYSQLI_BOTH)) {
            getReplyMessage($posts);
        }
        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
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
    loadMsg($cur['POSTID']);
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
