<?php
session_start();
include("dbInc.php");
?>

<?php
if($_SESSION['id']!=null){ // 如果登入過，則直接轉到登入後頁面
echo '<meta http-equiv=REFRESH CONTENT=0;url=index.php>';
}
else {
$acc = $_POST['account'];
$pwd = $_POST['password'];
$acc = preg_replace("/[^A-Za-z0-9]/","",$acc);
$pwd = preg_replace("/[^A-Za-z0-9]/","",$pwd);
if($acc!=NULL && $pwd!=NULL){
$sql = "SELECT ID, ACCOUNT, PWD FROM USER where ACCOUNT = '$acc'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
// 比對密碼
if($row['PWD']==md5($pwd)){
$_SESSION['id'] = $row['ACCOUNT'];
$_SESSION['pwd'] = $row['PWD'];
$_SESSION['nickName'] = $row['ID'];
echo '<meta http-equiv=REFRESH CONTENT=0;url=index.php>';
echo $_SESSION['PWD'];
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
         帳號：<input type="text" name="account"><br/>
         密碼：<input type="text" name="password"><br/>
         <input type="submit">
      </form>

</body></html>
