<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['ACCOUNT']!=null){ // 如果登入過，則直接轉到登入後頁面
    echo '<meta http-equiv=REFRESH CONTENT=0;url=index.php>';
}
else {
    $acc = $_POST['account'];
    $pwd = $_POST['password'];
    $acc = preg_replace("/[^A-Za-z0-9]/","",$acc);
    $pwd = preg_replace("/[^A-Za-z0-9]/","",$pwd);
    if($acc!=NULL && $pwd!=NULL){
        $sql = "SELECT * FROM USER where ACCOUNT = '$acc'";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        // 比對密碼
        if($row['PWD']==md5($pwd)){
            $_SESSION['ID'] = $row['ID'];
            $_SESSION['ACCOUNT'] = $row['ACCOUNT'];
            $_SESSION['PWD'] = $row['PWD'];
            $_SESSION['NICKNAME'] = $row['NICKNAME'];
            $_SESSION['NAME'] = $row['NAME'];
            $_SESSION['SEX'] = $row['SEX'];
            $_SESSION['EMAIL'] = $row['EMAIL'];
            echo '<meta http-equiv=REFRESH CONTENT=0;url=index.php>';
        }
    }

}
?>

<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<head>
</head>
   <body bgcolor="#ccccff">

      <form action="login.php" method="post">
         帳號：<input type="text" name="account" autocomplete="off"><br/>
         密碼：<input type="password" name="password" autocomplete="off"><br/>
         <br/>
         <input type="submit" value="Log in">
         <input type="button" onclick="window.location='register.php'" value="Sign Up">
      </form>

</body></html>
