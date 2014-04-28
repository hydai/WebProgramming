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
</head>
   <body bgcolor="#ccccff">

<?php
session_start();
?>
<?php
if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
    echo '<ul style="padding:0; color:red;">';
    foreach($_SESSION['ERRMSG_ARR'] as $msg) {
        echo '<li>',$msg,'</li>';
    }
    echo '</ul>';
    unset($_SESSION['ERRMSG_ARR']);
}
?>
        <form action="updateF.php" method="post">
        帳號：<?php echo $_SESSION['ACCOUNT'];?><br/>
        舊密碼：<input type="password" name="oldpassword"><br/>
        新密碼：<input type="password" name="password" placeholder="length >= 7"><br/>
        密碼確認：<input type="password" name="passwordrepeat" placeholder="Repeat above" autocomplete="off"><br/>
        姓名：<input type="text" name="name" placeholder="yourName" autocomplete="off" value="<?php echo $_SESSION['NAME'];?>"><br/>
        暱稱：<input type="text" name="nickname" placeholder="yourNickname" autocomplete="off" value="<?php echo $_SESSION['NICKNAME'];?>"><br/>
<!-- Not support Sex now
        性別：<input type="text" name="sex" placeholder="Male/Female" autocomplete="off" value="<?php echo $_SESSION['SEX'];?>"><br/>
-->
        E-mail：<input type="text" name="email" placeholder="youremail@example.com" autocomplete="off" value="<?php echo $_SESSION[EMAIL];?>"><br/>
        <input type="submit" value="Submit">
         <input type="button" onclick="window.location='index.php'" value="Cancel">
      </form>

</body></html>
