<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ('Php/config.php');
require_once ('Php/mysqli.php');
if($EnableFaucet != true){
  header('location:404.php');
}
if(isset($User)&&$User != ''){
}else{
  header('location:index.php');
}
if(isset($_POST['Claim'])){
  //$captcha = $_POST['g-recaptcha-response'];
  // $rsp  = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip$ip");
  //$arr = json_decode($rsp,TRUE);
  //if($arr['success']){
    $stmt = $con->prepare("select * from `FaucetClaims` where `UserID`=? ORDER BY `Time` DESC");
    $stmt->bind_param("s", $User);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();
    $row = mysqli_fetch_assoc($results);
    $LastClaimTime = $row['Time'];
    $_SESSION['LastClaimTime'] = $row['Time'];
    if($TimePerClaim <= ($curtime - $LastClaimTime)){
      $Amount = ($PricePerCoinUSD * $ClaimInUsdPerClick);
      $stmt = $con->prepare("INSERT INTO `FaucetClaims`(`UserID`, `Time`, `Amount`, `Ip`) VALUES (?,?,?,?)");
      $stmt->bind_param("sids", $User,$curtime,$Amount,$ip);
      $stmt->execute();
      $stmt->close();
      header('location:faucet.php');
      //Claim stuff
    }else{
    }
  //}else{
  //echo "Bad recaptcha"
  //}
}
?>
<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title><?php echo $Title ?> Faucet</title>
  </head>
  <?php include_once "assets/bases/Nav.php";?>
  <div class='row'>
    <div class='col-sm-2' style="background-color:blue;height:100%">
      <?php echo $SideBarAd1; ?>
    </div>
    <div class='col-sm-8'>
      <div>
        <!-- Put banner ads in these places -->
      </div>
        <div>
          <center>
            <form action="" method="post">
              <?php
              if(isset($_SESSION['LastClaimTime'])&&$_SESSION['LastClaimTime']!=''){
                $LastClaimTime = $_SESSION['LastClaimTime'];
                $TimeWait = ($curtime - $LastClaimTime) - $TimePerClaim;
                if($TimeWait <= 0){
                  $TimeWait = abs($TimeWait);
                  echo "Please wait $TimeWait seconds";
                }else{
                  echo "You can claim now!";
                }

              }
              ?>
              <br>
              <!--<div class="g-recaptcha" data-sitekey="<?php if(isset($RecapSiteKey)&&$RecapSiteKey != ''){echo $RecapSiteKey;}?>"></div> -->
              <input name="Claim" type="submit" class="button" value="Claim">
            </form>
        </center>
      </div>
    </div>
    <div class='col-sm-2' style="background-color:red;height:100%">
      <?php echo $SideBarAd2; ?>
    </div>
  </div>
<?php include_once "assets/bases/footer.php";?>
</html>
