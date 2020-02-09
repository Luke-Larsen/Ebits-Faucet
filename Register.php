<?php
require_once 'Php/config.php';
require_once 'Php/mysqli.php';

if(isset($_SESSION["UserID"])&& !empty($_SESSION["UserID"])){
  header('location:account.php');
}

function isDisposableEmail($email, $blacklist_path = null) {
    if (!$blacklist_path) $blacklist_path = __DIR__ . '/Php/disposable_email_blacklist.conf';
    $disposable_domains = file($blacklist_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $domain = mb_strtolower(explode('@', trim($email))[1]);
    return in_array($domain, $disposable_domains);
}
if(isset($_POST['Register'])) {
  //if(isset($_POST['g-recaptcha-response'])&& $_POST['g-recaptcha-response']){
      //$captcha = $_POST['g-recaptcha-response'];
      //$rsp  = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip$ip");
      //$arr = json_decode($rsp,TRUE);
      $stmt = $con->prepare("SELECT `ip`,`lastLogin`,`UserID` FROM `User` ");
      $stmt->execute();
      $result1 = $stmt->get_result();
      $num_of_rows = $result1->num_rows;
      $curtime = time();
      if(!$result1){
          echo "could not run query";
      }
      if($result1->num_rows == 0){
          echo"nothing in rows";
          $ipExist=false;
      }
      while ($row3 = $result1->fetch_assoc()) {
          if($ip == $row3['ip']){
              $ipExist = true;
              if($curtime-$row3['iptime']<=86400){//One day in secounds
                  $ipUsed = true;
              }
          }else{
              $ipUsed = false;
          }
      }
      $stmt->close();
      if($ipExist==false){
          $newIp = true;
      }

    if(isDisposableEmail($_POST['Email']) == FALSE){
      //if($arr['success']){
          if(isset($ipUsed)&&$ipUsed == false || $newIp == true){

            $Username = $_POST['User'];
            $LName = $_POST['Last_Name'];
            $Email = $_POST['Email'];
            $WA = $_POST['WalletAddr'];
            $parts = explode('@',$Email);
            if(strtolower($parts[1]) == 'gmail.com'){
                $Email = str_replace('.', '', $parts[0]);
                $Email = $Email . '@' . $parts[1];
            }
            $PW = password_hash($_POST['Password'], PASSWORD_DEFAULT);
            if(isset($_SESSION["refid"])&&!empty($_SESSION["refid"])){
              $refid = $_SESSION["refid"];
            }
            if(isset($_SESSION["refid"])&&!empty($_SESSION["refid"])){
              $stmt = $con->prepare("INSERT INTO `User`(`Email`, `refid`, `Username`, `Password`, `WalletAddr`, `lastLogin`, `ip`) VALUES (?,?,?,?,?,?,?)");
              $stmt->bind_param("sisssis", $Email,$refid, $Username, $PW,$WA,$curtime,$ip);
            }else{
              $stmt = $con->prepare("INSERT INTO `User`(`Email`, `Username`, `Password`, `WalletAddr`, `lastLogin`, `ip`) VALUES (?,?,?,?,?,?)");
              $stmt->bind_param("ssssis", $Email, $Username, $PW,$WA,$curtime,$ip);
            }
            if ($stmt->execute()) {
                $stmt->close();
                $stmt = $con->prepare("select * from User where Email=?");
                $stmt->bind_param("s", $Email);
                $stmt->execute();
                $stmt->close();
                $_SESSION["UserID"] = $UserIDFaucetUse;
                echo"<script>window.location = '/account.php?id=1337';</script>";
              } else {
                  echo "Error in creating account faucet creation";
                  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
              }
            }else{
                echo "Ip already used";
            }
        //}else{
        //    echo "Bad captcha";
        //}
      }else{
        echo "We don't accept this type of email";
      }
  //}else{
  //  echo "no captcha set";
  //}
}
?>
<html lang="en">
	<head>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<script type="text/javascript">
    function getusername(value){
        $.post("Php/varifier",{partialUsername:value}, function(data){
            $("#queryuser").html(data)
        });
    }
    function getemail(value){
        $.post("Php/varifierE",{partialUseremail:value}, function(data){
            $("#queryemail").html(data)
        });
    }
  </script>
	<body>
		<div class="row">
			<div class="6u 12u$(small)" style="text-align: center;">
				<h2>Register Here</h2>
						<form action="" method="post" name="RegisterForm" id="RegisterForm">
						  <div class="FormElement">
						    <input name="User" type="text" required class="TField" id="User" autocomplete="username" placeholder="Username" onChange="getusername(this.value)">
						  </div>
						  <div id="queryuser" class="Alertcolor"></div>
						  <div class="FormElement">
						    <input name="WalletAddr" type="text" required class="TField" id="WalletAddr" placeholder="WalletAddr">
						  </div>
						  <div class="FormElement">
						    <input name="Email" type="email" required class="TField" id="Email" autocomplete="email" placeholder="Email" onChange="getemail(this.value)">
						  </div>
						  <div id="queryemail" class="Alertcolor"></div>
						  <div class="FormElement">
						    <input name="Password" type="password" required class="TField" id="Password" autocomplete="new-password" placeholder="Password">
						  </div>
						  <center>
						    <!--<div class="g-recaptcha" data-sitekey="<?php echo $RecapSiteKey?>"></div> -->
						  </center>
						  <div class="FormElement">
                <h3>By clicking register you agree to our <br><a href='/PrivacyPolicy.html'target='blank'>Privacy Policy</a><br> and <a href='TermsAndConditions.php'target='blank'>Terms of Use</a></h3><br>
						    <input name="Register" type="submit" class="button" value="Register">
						  </div>
						</form>
						<h3 style="text-align: center;">Already have an account? Login <a href="login.php" class="Hyperlink">Here</a></h3>
			</div>
		</div>
	</body>
</html>
