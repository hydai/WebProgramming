<?php
session_start();
include_once("dbInc.php");
include_once("utility.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
} else {
    $isFriends = false;
}
?>
<?php
$temparray=explode("=",$_SERVER[QUERY_STRING]);
$homeID = (int)$temparray[1];
if ($homeID != 0) {
    $sql = "SELECT NAME, NICKNAME from USER WHERE ID = ?";
    $mysqli = getMysqli();
    /* create a prepared statement */
    if ($stmt = $mysqli->prepare($sql)) {
    
        /* bind parameters for markers */
        $stmt->bind_param("i", $homeID);
    
        /* execute query */
        $stmt->execute();

        /* bind variables to prepared statement */
        $stmt->bind_result($tname, $tnickname);

        /* fetch values */
        if ($stmt->fetch()) {
            $homeInfo = array (
                "NAME" => $tname,
                "NICKNAME" => $tnickname
            );
        } else {
            $homeID = 0;
        }

        /* close statement */
        $stmt->close();
    }
    closeMysqli($mysqli);
    /*
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
     */
}
?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<head>
    <title>Fakebook - Home</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="http://m101.nthu.edu.tw/~s101062124/bootstrap/js/bootstrap.min.js"></script>
    <link href='http://m101.nthu.edu.tw/~s101062124/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
    <!-- use google font API -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700' rel='stylesheet' type='text/css'>
    <link href='main.css' rel='stylesheet' type='text/css'>
</head>
<body bgcolor="#ccccff">
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">Fakebook</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <form class="navbar-form navbar-left" role="search" action="findFriend.php" method="post">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search friends" name="friendAccount" autocomplete="off">
        </div>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Search</button>
      </form>
      <div class="navbar-form navbar-right">
<button class="btn btn-default" onclick="window.location='index.php'"><span class="glyphicon glyphicon-home"></span> 回到首頁</button>
<button class="btn btn-default" onclick="window.location='update.php'"><span class="glyphicon glyphicon-cog"></span> 修改資料</button>
<button class="btn btn-default" onclick="window.location='logout.php'"><span class="glyphicon glyphicon-road"></span> 登出</button>
      </div>
      <?php
if ($homeID != 0) {
    friendOption($homeID);}
?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="page-header" id="ph">
<?php
if ($homeID == 0) {
    echo "<h1>Sorry, nobody is here.</h1>";
}
else {
    echo "<h1>Welcome to ".$homeInfo['NAME']."(".$homeInfo['NICKNAME'].")'s page!</h1>";
}
?>
</div>
<?php
loadPostWall($homeID, $homeInfo);
?>
</body>
</html>
