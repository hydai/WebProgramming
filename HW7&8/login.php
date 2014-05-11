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
    <link href='http://fonts.googleapis.com/css?family=Lemon|Codystar|PT+Mono' rel='stylesheet' type='text/css'>
    <link href='main.css' rel='stylesheet' type='text/css'>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body bgcolor="#ccccff">

    <div id="init">
        <div id="loginC">
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
                    <tr>
                        <td>
                            <input type="submit" value="Log in">
                        </td>
                        <td>
                            <input type="button" onclick="window.location='register.php'" value="Sign Up">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="regC">
<?php
session_start();
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
                帳號：
                <input type="text" name="account" placeholder="A-Z, 0-9, a-z, length >= 6" autocomplete="off">
                <br/>密碼：
                <input type="password" name="password" placeholder="length >= 7" autocomplete="off">
                <br/>密碼確認：
                <input type="password" name="passwordrepeat" placeholder="Repeat above" autocomplete="off">
                <br/>姓名：
                <input type="text" name="name" placeholder="yourName" autocomplete="off">
                <br/>暱稱：
                <input type="text" name="nickname" placeholder="yourNickname" autocomplete="off">
                <br/>
                <!--Not support Sex nuw
             性別：<input type="text" name="sex" placeholder="Male/Female" autocomplete="off"><br/>
        -->
                E-mail：
                <input type="text" name="email" placeholder="youremail@example.com" autocomplete="off">
                <br/>
                <input type="submit">
                <input type="button" onclick="window.location='login.php'" value="Cancel">
            </form>
        </div>
    </div>
</body>

</html>