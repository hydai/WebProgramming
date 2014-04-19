<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']!=null){ // 如果登入過，則直接轉到登入後頁面
    echo '<meta http-equiv=REFRESH CONTENT=0;url=index.php>';
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
      <form action="reg.php" method="post">
         帳號：<input type="text" name="account" placeholder="A-Z, 0-9, a-z, length >= 6"><br/>
         密碼：<input type="text" name="password" placeholder="length >= 7"><br/>
         姓名：<input type="text" name="name" placeholder="yourName"><br/>
         暱稱：<input type="text" name="nickname" placeholder="yourNickname"><br/>
         性別：<input type="text" name="sex" placeholder="Male/Female"><br/>
         E-mail：<input type="text" name="email" placeholder="youremail@example.com"><br/>
         <input type="submit">
      </form>

</body></html>
