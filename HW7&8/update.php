<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){ // 如果還沒登入過，則直接轉到登入頁面
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
} else {

}
function getHP($target) {
    $findSql = "SELECT ID FROM USER where ID='$target'";
    $findResult = mysql_query($findSql);
    if (mysql_num_rows($findResult) > 0) {
        $getHPSql = "SELECT * FROM HEADPHOTO where MASTERID='$target'";
        $getResult = mysql_query($getHPSql);
        if (mysql_num_rows($getResult) > 0) {
            $tmp = mysql_fetch_array($getResult);
            $filename = "./fileArea/photos/".$tmp['PID'].".".$tmp['FILETYPE'];
        } else {
            $filename = "./fileArea/default.png";
        }
    } else {
        $filename = "./fileArea/default.png";
    }
    return $filename;
}
?>

<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

<head>
    <title>Fakebook - Update</title>
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
                    <button type="submit" class="btn btn-default">
                        <span class="glyphicon glyphicon-search"></span>Search</button>
                </form>
                <div class="navbar-form navbar-right">
                    <button class="btn btn-default" onclick="window.location='update.php'">
                        <span class="glyphicon glyphicon-cog"></span>修改資料</button>
                    <button class="btn btn-default" onclick="window.location='logout.php'">
                        <span class="glyphicon glyphicon-road"></span>登出</button>
                </div>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <div id="init">
        <div id="updateC">
            <form action="updateF.php" method="post" enctype="multipart/form-data">
                    <?php
                        echo '<img src="'.getHP($_SESSION["ID"]).'" alt="Head photo" class="img-thumbnail hpb">';
                    ?>
                        <input id="fileC" type="file" name="file">
<hr>
                <table>
                    <tr>
                        <td align="left">帳號：</td>
                        <td align="right">
                            <?php echo $_SESSION[ 'ACCOUNT'];?>
                        </td>
                    </tr>
                    <tr>
                        <td align="left">舊密碼：</td>
                        <td align="right">
                            <input type="password" name="oldpassword">
                        </td>
                    </tr>
                    <tr>
                        <td align="left">新密碼：</td>
                        <td align="right">
                            <input type="password" name="password" placeholder="length >= 7">
                        </td>
                    </tr>
                    <tr>
                        <td align="left">密碼確認：</td>
                        <td align="right">
                            <input type="password" name="passwordrepeat" placeholder="Repeat above" autocomplete="off">
                        </td>
                    </tr>
                    <tr>
                        <td align="left">姓名：</td>
                        <td align="right">
                            <input type="text" name="name" placeholder="yourName" autocomplete="off" value="<?php echo $_SESSION['NAME'];?>">
                        </td>
                    </tr>
                    <tr>
                        <td align="left">暱稱：</td>
                        <td align="right">
                            <input type="text" name="nickname" placeholder="yourNickname" autocomplete="off" value="<?php echo $_SESSION['NICKNAME'];?>">
                        </td>
                    </tr>
                    <tr>
                        <td align="left">E-mail：</td>
                        <td align="right">
                            <input type="text" name="email" placeholder="youremail@example.com" autocomplete="off" value="<?php echo $_SESSION[EMAIL];?>">
                        </td>
                    </tr>
                </table>
<div class="buttons">
                            <input type="submit" value="Submit" class="btn btn-default">
                            <input type="button" onclick="window.location='index.php'" value="Cancel" class="btn btn-default">
</div>
            </form>
        </div>
        <div id="errmsg">
            <script type="text/javascript">
            $('#errmsg').hide();
            </script>
<?php
session_start();
if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
    echo '<script type="text/javascript">$("#errmsg").slideToggle(100);</script>';
    echo '<h3>Update Error</h3>';
    echo '<ul>';
    foreach($_SESSION['ERRMSG_ARR'] as $msg) {
        echo '<li>',$msg,'</li>';
    }
    echo '</ul>';
    unset($_SESSION['ERRMSG_ARR']);
}
?>
        </div>
    </div>

</body>

</html>
