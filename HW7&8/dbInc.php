<?php

$db_server = "localhost";
$db_user = "fakebook";
$db_passwd = "facebook";
$db_name = "fakebook";

function getMysqli() {
    $mysqli = new mysqli($host, $user, $password, $database);

    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
    }
    /* change character set to utf8 */
    if (!$mysqli->set_charset("utf8")) {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
    return $mysqli;
}
function closeMysqli($t) {
    $t->close();
}
/* old mysql
if(!@mysql_connect($db_server, $db_user, $db_passwd)){
        die("無法對資料庫連線");
}

mysql_query("SET NAMES utf8");

if(!@mysql_select_db($db_name)){
        die("無法使用資料庫");
}
 */
?>
