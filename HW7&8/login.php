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
    <title>Fakebook - Login</title>
    <!-- use google font API -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700' rel='stylesheet' type='text/css'>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="http://m101.nthu.edu.tw/~s101062124/bootstrap/js/bootstrap.min.js"></script>
    <link href='http://m101.nthu.edu.tw/~s101062124/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
    <link href='main.css' rel='stylesheet' type='text/css'>
<script type="text/javascript">
$(function(){
    $('#regC').hide();
    $('#regBt').on('click', function(){
        $("#regC").slideToggle(500);
        $("#errmsg").hide();
    });
    $('#cancelBt').on('click', function(){
        $("#regC").slideToggle(500);
        $("#errmsg").hide();
    });
});
</script>
</head>

<body bgcolor="#ccccff">

    <div id="init">
        <div id="loginC">
        <h1>Fakebook</h1>
            <form action="login.php" method="post">
                <table>
                    <tr>
                        <td align="left">帳號：</td>
                        <td align="right">
                            <input type="text" name="account" autocomplete="off">
                        </td>
                    </tr>
                    <tr>
                        <td align="left">密碼：</td>
                        <td align="right">
                            <input type="password" name="password" autocomplete="off">
                        </td>
                    </tr>
                </table>
<div class="buttons">
                            <input type="submit" value="Log in" class="btn btn-default btn-lg">
                            <input type="button" id="regBt" class="btn btn-default btn-lg" value="Sign Up">
</div>
            </form>
        </div>
        <div id="errmsg">
    <script type="text/javascript">$('#errmsg').hide();</script>
<?php
session_start();
if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
    echo '<script type="text/javascript">$("#errmsg").slideToggle(100);</script>';
    echo '<h3>Register Error</h3>';
    echo '<ul>';
    foreach($_SESSION['ERRMSG_ARR'] as $msg) {
        echo '<li>',$msg,'</li>';
    }
    echo '</ul>';
    unset($_SESSION['ERRMSG_ARR']);
}
?>
    </div>
        <div id="regC">
            <form action="reg.php" method="post">
<table>
<tr>
<td align="left">帳號：</td>
<td align="right"><input type="text" name="account" placeholder="A-Z, 0-9, a-z, length >= 6" autocomplete="off"></td>
</tr>
<tr>
<td align="left">密碼：</td>
<td align="right"><input type="password" name="password" placeholder="length >= 7" autocomplete="off">
</td>
</tr>
<tr>
<td align="left">確認：</td>
<td align="right"><input type="password" name="passwordrepeat" placeholder="Repeat above" autocomplete="off"></td>
</tr>
<tr><td align="left">姓名：</td>
<td align="right"><input type="text" name="name" placeholder="yourName" autocomplete="off">
                </td></tr>
<tr><td align="left">暱稱：</td>
<td align="right"><input type="text" name="nickname" placeholder="yourNickname" autocomplete="off"></td>
</tr>
<tr><td align="left">信箱：</td>
                <td align="right"><input type="text" name="email" placeholder="youremail@example.com" autocomplete="off"></td>
</tr>
</table>
<div class="buttons">
<input type="submit" value="Submit" class="btn btn-default">
<input type="button" id="cancelBt" value="Cancel" class="btn btn-default">
</div>
            </form>
        </div>
    </div>
</body>

</html>
