<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']==null){ // 如果還沒登入過，則直接轉到登入頁面
    echo '<meta http-equiv=REFRESH CONTENT=0;url=login.php>';
} else {

}
?>

<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<head>
    <title>Fakebook - Update</title>
    <!-- use google font API -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700' rel='stylesheet' type='text/css'>
    <link href='main.css' rel='stylesheet' type='text/css'>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>
   <body bgcolor="#ccccff">

    <div id="init">
    <div id="updateC">
        <form action="updateF.php" method="post">
    <table>
        <tr><td align="left">帳號：</td><td align="right"><?php echo $_SESSION['ACCOUNT'];?></td></tr>
        <tr><td align="left">舊密碼：</td><td align="right"><input type="password" name="oldpassword"></td></tr>
        <tr><td align="left">新密碼：</td><td align="right"><input type="password" name="password" placeholder="length >= 7"></td></tr>
        <tr><td align="left">密碼確認：</td><td align="right"><input type="password" name="passwordrepeat" placeholder="Repeat above" autocomplete="off"></td></tr>
        <tr><td align="left">姓名：</td><td align="right"><input type="text" name="name" placeholder="yourName" autocomplete="off" value="<?php echo $_SESSION['NAME'];?>"></td></tr>
        <tr><td align="left">暱稱：</td><td align="right"><input type="text" name="nickname" placeholder="yourNickname" autocomplete="off" value="<?php echo $_SESSION['NICKNAME'];?>"></td></tr>
        <tr><td align="left">E-mail：</td><td align="right"><input type="text" name="email" placeholder="youremail@example.com" autocomplete="off" value="<?php echo $_SESSION[EMAIL];?>"></td></tr>
        <tr>
        <td><input type="submit" value="Submit"></td>
         <td><input type="button" onclick="window.location='index.php'" value="Cancel"></td>
    </tr>
    </table>
      </form>
    </div>
        <div id="errmsg">
    <script type="text/javascript">$('#errmsg').hide();</script>
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

</body></html>
