<?php
require_once ('Php/config.php');
require_once ('Php/mysqli.php');

if(isset($_POST['enter'])){
  $username = $_POST['username'];
  $password = $_POST['password'];
  if(isset($secret) && $secret != ''){
    $captcha = $_POST['g-recaptcha-response'];
    $rsp  = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip$ip");
    $arr = json_decode($rsp,TRUE);
  }
  if($arr['success'] || (!isset($secret) && $secret != '')){
    $stmt = $con->prepare("select * from user where Email=? or `Username`=?");
    $stmt->bind_param("ss", $EM,$EM);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();
    $row = mysqli_fetch_assoc($results);
    $hashPW = password_hash($PW, PASSWORD_DEFAULT);
    $hash = $row['Password'];
    $rowEM = $row['Email'];
    $User = $row['UserID'];
    if (password_verify($PW, $hash)) {
        session_regenerate_id();
        $_SESSION["UserID"] = $row['UserID'];
        $OneMoreTime = "UPDATE user SET iptime = $curtime, ip = '$ip' WHERE UserID = $User";
        echo "<script>window.location = '$HTTP://$URL/account.php';</script>";
        exit;
    }elseif(!password_verify($PW, $hash)){
        echo "<h4>Invalid email or password.</h4>";
    }else{
        echo "<h4>An unexpected error has occured please try again later.</h4>";
    }
  }else{
    echo "bad Captcha";
  }
}
?>
<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <title><?php echo $Title ?></title>
  </head>
  <body>
    Welcome please login in order to begain claiming Ebits
    <form action="" method="post">
      <input type="text" name='username' class="form-control" placeholder="username/email" required>
      <input type="password" name='password' class="form-control" placeholder="password" required>
      <input type="submit" class="btn btn-danger" name="enter" value="Enter" placeholder="Enter">
    </form>
  </body>
</html>
