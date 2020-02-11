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
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
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
    <h4>If you don't have an account <a href='Register.php'>register here</a></h4>
    <?php include_once "assets/bases/footer.php";?>
  </body>
</html>
