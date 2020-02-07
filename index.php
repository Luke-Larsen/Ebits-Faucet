<?php


require_once ('Php/config.php');
require_once ('Php/mysqli.php');
if(isset($User)&&$User != ''){
    header('location:account.php');
}else{
}
if(isset($_POST['enter'])){
  $username = $_POST['username'];
  //$captcha = $_POST['g-recaptcha-response'];
  // $rsp  = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip$ip");
  //$arr = json_decode($rsp,TRUE);
  //if($arr['success']){
    $stmt = $con->prepare("select * from User where Email=? or `Username`=?");
    $stmt->bind_param("ss", $username,$username);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();
    $PW = $_POST['password'];
    $row = mysqli_fetch_assoc($results);
    $hash = $row['Password'];
    $rowEM = $row['Email'];
    $User = $row['UserID'];
    if (password_verify($PW, $hash)) {
        session_regenerate_id();
        $_SESSION["UserID"] = $row['UserID'];
        $OneMoreTime = "UPDATE user SET iptime = $curtime, ip = '$ip' WHERE UserID = $User";
        echo "<script>window.location = 'account.php';</script>";
        exit;
    }elseif(!password_verify($PW, $hash)){
        echo "<br><br><h4>Invalid email or password.</h4>";
    }else{
        echo "<h4>An unexpected error has occured please try again later.</h4>";
    }
  //}else{
  //  echo "bad Captcha";
  //}
}
?>
<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title><?php echo $Title ?></title>
  </head>
  <body data-spy="scroll" data-target=".navbar" data-offset="60">
    <?php include_once "assets/bases/Nav.php";?>
    Welcome please login in order to begain claiming Ebits
    <form action="" method="post">
      <input type="text" name='username' class="form-control" placeholder="username/email" required>
      <input type="password" name='password' class="form-control" placeholder="password" required>
      <input type="submit" class="btn btn-danger" name="enter" value="Enter" placeholder="Enter">
    </form>
    <?php include_once "assets/bases/footer.php";?>
  </body>
</html>
