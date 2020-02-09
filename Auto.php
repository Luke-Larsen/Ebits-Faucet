<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('Php/config.php');
require_once ('Php/mysqli.php');
if($EnableAutoFaucet != true){
  header('location:404.php');
}
if(isset($User)&&$User != ''){
}else{
  header('location:index.php');
}
//SORT LINK STUFF
if(isset($_GET['id']) &&!empty($_GET['id'])){
	$ShortlinkCheck = $_GET['id'];
}
if(isset($ShortlinkCheck) &&!empty($ShortlinkCheck)){
    $ShortLinkCachCheck = $_SESSION['ShortLinkCheck'];
    $id = $_SESSION['ShortLinkid'];
	if($ShortlinkCheck == $ShortLinkCachCheck){
            echo "Success!<br>";
            $GiveReward = mysqli_query($con,"UPDATE `User` SET `Tokens`=`Tokens`+1 WHERE `UserID` = $User");
            $LogClaim = mysqli_query($con,"INSERT INTO `ShortLinkClaims`(`UserID`, `ShortlinkId`, `Time`) VALUES ('$User','$id','$curtime')");
            $AddingATimeClaimed = mysqli_query($con, "UPDATE `ShortLinks` SET `TimesClicked`=`TimesClicked`+1 WHERE `ShortlinkId` = $id");
            $_SESSION['ShortLinkCheck'] = '';
            $_SESSION['ShortLinkid'] = '';
	}
}

if(isset($_POST['submit'])){
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $shortlinksS = mysqli_query($con, "SELECT * FROM `ShortLinks` WHERE ShortlinkId=$id");
    While($ShortlinksFetchedPush = mysqli_fetch_assoc($shortlinksS)){
        $RandomNumberForCheck = rand(1,100);
        $APILink = $ShortlinksFetchedPush['APILink'];
        $API = $ShortlinksFetchedPush['API'];
        $_SESSION['ShortLinkCheck'] = $RandomNumberForCheck;
        $_SESSION['ShortLinkid'] = $ShortlinksFetchedPush['ShortlinkId'];;
        //$url = $APILink .'?api='. $API . "&url=$HTTP://$URL/Auto.php?id=" . $RandomNumberForCheck . '&format=text';
        $url = $APILink .'?api='. $API . "&url=http://www.lukehanslarsen.com/EbitsFaucetR/Auto.php?id=" . $RandomNumberForCheck . '&format=text';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        curl_close($ch);
        echo "<script>window.location = '$output';</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <title><?php echo $Title ?> Auto Faucet</title>
  </head>
  <body>
    <?php include_once "assets/bases/Nav.php";?>
    <div class='row'>
      <div class='col-sm-2' style="background-color:#D3D3D3;min-height:100%">
        <?php echo $SideBarAd2; ?>
      </div>
      <div class='col-sm-8'>
        <div>
          <!-- Put banner ads in these places -->
        </div>
          <div>
            <center>
              <?php
              $clause = '';
              $Tokens = mysqli_query($con, "SELECT `Tokens` FROM `User` WHERE `UserID` =  $User");
              $shortlinkclaims = mysqli_query($con, "SELECT ShortlinkId,Time FROM ShortLinkClaims WHERE UserID = $User");
              if(isset($shortlinkclaims)&& !empty($shortlinkclaims) &&$shortlinkclaims->num_rows != 0){
                  while($ShortlinksClaimFetched = mysqli_fetch_assoc($shortlinkclaims)){
                      $timeClaimed = $ShortlinksClaimFetched['Time'];
                      if($curtime - $timeClaimed < '86400'){
                          $array[] = $ShortlinksClaimFetched['ShortlinkId'];
                      }
                  }
                  if(!empty($array) && isset($array)){
                      foreach ($array as $a) {$clause.= "'$a',";}
                      $clause=substr($clause,0,-1);
                      if(empty($array)){
                          $clause = '0';
                      }
                  }
                  if(empty($clause)){
                      $shortlinks = mysqli_query($con, "SELECT * FROM `ShortLinks`");
                  }else{
                      $shortlinks = mysqli_query($con, "SELECT * FROM `ShortLinks` WHERE `id` NOT IN ($clause) ORDER BY `id` ASC");
                  }
              }else{
                 $shortlinks = mysqli_query($con, "SELECT * FROM `ShortLinks`");
              }
              if(isset($shortlinks)&&!empty($shortlinks) && $shortlinks->num_rows != 0){
                  While($ShortlinksFetched = mysqli_fetch_assoc($shortlinks)){
                      $Company = $ShortlinksFetched['Name'];
                      $Id = $ShortlinksFetched['ShortlinkId'];
                      echo "<form method='post' action=''><input name='submit' type='submit' class='button' value='$Company'/> <input type='hidden' name='id' value='$Id'/></form>";
                  }
              }else{
                  echo "You have done all the shortlinks you can for today D:";
              }
              $Tokens = mysqli_fetch_assoc($Tokens);
              $Tokens = $Tokens['Tokens'];
              if($Tokens > 0){
                $AutoClaims = mysqli_query($con, "SELECT * FROM `AutoClaims` WHERE `UserID` = $User ORDER BY `Time` DESC");
                $TotalTime = $AutoFaucetTimer * $Tokens;
                if(!empty(mysqli_num_rows($AutoClaims))){
                  $LastClaim = mysqli_fetch_assoc($AutoClaims);
                  $LastClaimTime = $LastClaim['Time'];
                }else{
                  $LastClaimTime = $curtime - ($AutoFaucetTimer);
                }
                $timewait = $curtime - $LastClaimTime;
                $timewaitShow = $AutoFaucetTimer - $timewait;
                if($timewait >= $AutoFaucetTimer){
                  $Amount = $ClaimInUsdPerClickAuto * $PricePerCoinUSD;
                  $QuickClaim = mysqli_query($con, "INSERT INTO `AutoClaims`(`UserID`, `Time`, `Amount`, `Ip`) VALUES ('$User','$curtime','$Amount','$ip')");
                  $QuickClaimBalanceAd = mysqli_query($con, "UPDATE `User` SET `Balance`=`Balance`+$Amount,`Tokens`=Tokens - 1 WHERE `UserID` = $User");
                  echo "<script>location.reload();</script>";
                }
              }else{
                echo "You don't have any tokens. Please use shortlinks to get more. One per shortlink!";
              }
              ?>
              <script>
                  var seconds = <?php echo "$timewaitShow;"; ?>;
                  function setSecond(timenumber) {
                      seconds = timenumber;
                  }
                  function secondPassed() {
                      var minutes = Math.round((seconds - 30) / 60);
                      var remainingSeconds = seconds % 60;
                      if (remainingSeconds < 10) {
                          remainingSeconds = '0' + remainingSeconds;
                      }
                      document.getElementById('countdown').innerHTML = minutes + ':' + remainingSeconds;
                      if (seconds == 0) {
                        location.reload();
                      } else {
                          seconds--;
                      }
                  }
                  var countdownTimer = setInterval('secondPassed()', 1000);
              </script>
              <div class='Alertcolor' id='countdownhead'>Already claimed reward wait <div id='countdown' class='Alertcolor'></div></div><br>
            </center>
          </div>
        <div>
<!-- Put banner ads in these places -->
        </div>
      </div>
      <div class='col-sm-2' style="background-color:#D3D3D3;min-height:100%">
        <?php echo $SideBarAd2; ?>
      </div>
    </div>
    <?php include_once "assets/bases/footer.php";?>
  </body>
</html>
