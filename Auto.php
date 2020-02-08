<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ('Php/config.php');
require_once ('Php/mysqli.php');
if($EnableAutoFaucet != true){
  header('location:404.php');
}

//SORTLINKSTUFF
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
<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title><?php echo $Title ?> Auto Faucet</title>
  </head>
  <body>
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
              <?php
              $clause = '';
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
              ?>
            </center>
          </div>
        <div>
<!-- Put banner ads in these places -->
        </div>
      </div>
      <div class='col-sm-2' style="background-color:red;height:100%">
        <?php echo $SideBarAd2; ?>
      </div>
    </div>
    <?php include_once "assets/bases/footer.php";?>
  </body>
</html>
