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
      echo "<script>location.reload();</script>";
    }else{
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
    <title><?php echo $Title ?> Faucet</title>
  </head>
  <?php include_once "assets/bases/Nav.php";?>
  <div class='row'>
    <div class='col-sm-2' style="background-color:#D3D3D3;min-height:100%">
      <?php echo $SideBarAd2; ?>
    </div>
    <div class='col-sm-8'>
      <div>
        <center>
          <h4>OfferWallet</h4>
          <div>
            <div class="tab">
              <button class="tablinks" onclick="openOfferwall(event, 'Offertoro')" id="defaultOpen">Offertoro</button>
              <button class="tablinks" onclick="openOfferwall(event, 'Asimag')"></button>
              <button class="tablinks" onclick="openOfferwall(event, 'adsendmedia)"></button>
            </div>

              <div id="Offertoro" class="tabcontent">
                <!--PLACE YOUR Offertoro INTERGRATION HERE send postback to http://www.YOURWEBSITEHERE.WHATEVER/Php/pbOfferToro.php-->
              </div>
              <div id="Asimag" class="tabcontent">
                <!--PLACE YOUR Asimag INTERGRATION HERE send postback to http://www.YOURWEBSITEHERE.WHATEVER/Php/pbAsimag.php-->
              </div>
              <div id="adsendmedia" class="tabcontent">
                <!--PLACE YOUR adsendmedia INTERGRATION HERE send postback to http://www.YOURWEBSITEHERE.WHATEVER/Php/pbAdsendmedia.php-->
              </div>


            <script>
            function openOfferwall(evt, cityName) {
              var i, tabcontent, tablinks;
              tabcontent = document.getElementsByClassName("tabcontent");
              for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
              }
              tablinks = document.getElementsByClassName("tablinks");
              for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
              }
              document.getElementById(cityName).style.display = "block";
              evt.currentTarget.className += " active";
            }
            // Get the element with id="defaultOpen" and click on it
              openOfferwall(event, 'Account Settings');
            </script>
        </center>
      </div>
    </div>
    <div class='col-sm-2' style="background-color:#D3D3D3;min-height:100%">
      <?php echo $SideBarAd2; ?>
    </div>
  </div>
<?php include_once "assets/bases/footer.php";?>
</html>
