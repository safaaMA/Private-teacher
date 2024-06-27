<?php 
if(@$_COOKIE["userLogin"] == 1){
    if($_COOKIE["userToken"]== '100200300400500600'){

        header("Location: ./Admin.php");
        exit();
    }
    else{
        header("Location: ./studentPage.php");
        exit();

    }
}else{

}
include "./leonardo.php";
global $connect;
mysqli_set_charset($connect, 'utf8');
$error=null;


$code = @$_POST["code"];
$password = @$_POST["password"];

if(isset($_POST['send'])){
    if(empty($code) || empty($password)){
        $error = "<p>يرجى عدم ترك الحقول فارغه </p>";
        $welcome = "";
    }else{
        $sql = "SELECT * FROM students WHERE code='$code' AND password='$password'";
        $result = mysqli_query($connect, $sql);
        if(mysqli_num_rows($result) > 0){
            $RowUserInfo = mysqli_fetch_array($result);
            $userNum = $RowUserInfo['code'];
            $UserPass = $RowUserInfo['password'];
            $UserToken = $RowUserInfo['token'];
            $Rank = $RowUserInfo['Rank'];
            if($UserPass != $password){
                $error = "<p class='error'>عذراً يرجى كتابة كلمة السر بصورة صحيحة<i class='fa-solid fa-circle-exclamation'></i> </p>";
                $welcome = "";
            }else{  
                setcookie('userRank',$Rank, time() + (86400 * 365), "/");
                setcookie('userToken',$UserToken, time() + (86400 * 365), "/");
                setcookie('userLogin','1', time() + (86400 * 365), "/");
                if($_COOKIE["userToken"]== '100200300400500600'){
                header("Location: ./Admin.php");
                exit();
                }
                else{
                    header("Location: ./studentPage.php");
                    exit();

                }
            }
        }else{
            $error = "  <p class='error'>لايوجد حساب لهاذا الكود </p>";
            $welcome = "";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginstyle.css">
    <title>تسجيل الدخول</title>


</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="Logo.png" alt="شعار">
    </div>
    <div class="login-form">
        <h2>تسجيل الدخول</h2>
        <form action="" method="post">
            <input type="text" name="code" placeholder="الرقم التعريفي"><br>
            <input type="password" name="password" placeholder="الرمز السري"><br>
            <input type="submit" name="send" value="تسجيل الدخول">
            <?php echo $error;?>
        </form>
      
    </div>
</div>

</body>
</html>
