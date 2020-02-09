<?php
require_once ('Php/config.php');
require_once ('Php/mysqli.php');
require_once ('Php/easyEbits.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($User)&&$User != ''){
}else{
  header('location:index.php');
}
$GetInfo = $con->query("SELECT `Balance`,`WalletAddr` FROM `User` WHERE `UserID` =  '$User'");
$GetInfo = mysqli_fetch_assoc($GetInfo);
$Balance = $GetInfo['Balance'];
$WalletAddr = $GetInfo['WalletAddr'];
if(isset($GetInfo['ExpressCryptoId'])&& $GetInfo['ExpressCryptoId'] != ''){
  $ExpressCryptoId = $GetInfo['ExpressCryptoId'];
}
if(isset($_POST['enter'])){
  $Amount = $_POST['Amount'];
  if(!($Amount > $Balance)){
    $Ebits = new Ebits($VPSUser,$VPSPassword,$VPSHost,$VPSPort);
    if($Ebits->status != 0){
      $Ebits->sendtoaddress($WalletAddr, $Amount);
      if($this->response != FALSE){
        $RemoveBalance = $con->query("UPDATE `User` SET `Balance`=`Balance`-$Amount WHERE `UserID` = '$User'");
        echo "<script>location.reload();</script>";
      }else{
        echo "Error : " . $Ebits->error;
      }
    }else{
      echo "Unable to connect to wallet";
    }
  }else{
    echo "You do not have that much";
  }
}
if(isset($_POST['SetId'])){
  $ExpressCryptoId = htmlspecialchars($_POST['ExpressCryptoId'], ENT_QUOTES, 'UTF-8');
  if($ExpressCryptoId != ''){
      $url1 = 'https://expresscrypto.io/public-api/v2/checkUserHash';
      $fields1 = array(
          'api_key' => $ECSecret,
          'userId'=>$ExpressCryptoId,
          'user_token'=> $ECWord,
      );
      $postvars1 = http_build_query($fields1);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url1);
      curl_setopt($ch, CURLOPT_POST, count($fields1));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result1 = curl_exec($ch);
      curl_close($ch);
      $result1 = json_decode($result1);
      $errorcode = $result1->{'status'};
      if ($errorcode == 200) {
        $RemoveBalance = $con->query("UPDATE `User` SET `ExpressCryptoId`=$ExpressCryptoId WHERE  `UserID` = '$User'");
        echo "<script>location.reload();</script>";
      }else{
        echo "<script>window.location.href = 'withdraw.php?e=1';</script>";
      }
    }
}
if(isset($_POST['exchange'])){
  $Amount = $_POST['Amount'];
  if(!($Amount > $Balance)){
    //DO THE COVERSTION FOR THE CRYPTO
    $response = file_get_contents("https://blockchain.info/tobtc?currency=USD&value=1.00");
    $response = json_decode($response);
    $OneUsdBTC = sprintf('%.8F', $response);
    $ConvertedAmountBTC = $OneUsdBTC * $PricePerCoinOneUSD * $Amount;
    //DO THE COVERSTION FOR THE CRYPTO

    $url = 'https://expresscrypto.io/public-api/v2/sendPayment';
    $sendBtcAmount = $WithdrawlAmountN * 100000000;
    $fields = array(
        'api_key' => $ECSecret,
        'userId' => $ExpressCryptoId,
        'currency' => 'BTC',
        'user_token'=> $ECWord,
        'amount' => $ConvertedAmountBTC,
        'ip_user' => $ip,
        'payment_type'=> 'Normal'
    );
    $postvars = http_build_query($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result);
    $errorcode = $result->{'status'};
    $Zerout = $row1['Balance'] - $WithdrawlAmountN;
    if ($errorcode == 200) {
      $RemoveBalance = $con->query("UPDATE `User` SET `Balance`=`Balance`-$Amount WHERE `UserID` = '$User'");
      echo "<script>window.location.href = 'index.php';</script>";
    }else{
      echo "Error with Express Crypto";
    }
  }else{
    echo "You don't have a big enough balance";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <title><?php echo $Title ?> Withdraw</title>
  </head>
  <body data-spy="scroll" data-target=".navbar" data-offset="60">
    <?php include_once "assets/bases/Nav.php";?>
    <div id='wrap'>
      <div id='maindfas'>
    <div class='row'>
      <div class='col-sm-2' style="background-color:#D3D3D3;height:100%">
        <?php echo $SideBarAd2; ?>
      </div>
    <div class='col-sm-4'>
      <form action="" method="post">
        <input type="text" name='Amount' class="form-control" value="<?php echo $Balance; ?>" required>
        <input type="submit" class="btn btn-danger" name="enter" value="Enter" placeholder="Enter">
      </form>
    </div>
    <div class='col-sm-4'>
      <?php
        //check if the user has his Express Crypto Id also check if its enabled
        if($ECEnabled == true){
          if($ECSecret != ''){
            if($ECWord != ''){
              if(!empty($ExpressCryptoId)){
                echo "
                <form action='' method='post'>
                  <input type='text' name='Amount' class='form-control' value='$Balance' required>
                  <input type='submit' class='btn btn-danger' name='exchange' value='Enter' placeholder='Enter'>
                </form>
                ";
              }else{
                echo"
                In order to exchange and withdraw to ExpressCrypto Please set your expressCryptoId
                <form action='' method='post'>
                  <input type='text' name='ExpressCryptoId' class='form-control' placeholder='ExpressCryptoId' required>
                  <input type='submit' class='btn btn-danger' name='SetId' value='SetId' placeholder='SetId'>
                </form>
                ";
              }
            }else{
              echo "Key word not set";
            }
          }else{
            echo "Secret Not set";
          }
        }
      ?>
      </div>
      <div class='col-sm-2' style="background-color:#D3D3D3;min-height:100%">
        <?php echo $SideBarAd2; ?>
      </div>
    </div>
    </div>
  </div>
  <?php include_once "assets/bases/errors.php";?>
    <?php include_once "assets/bases/footer.php";?>
  </body>
</html>
