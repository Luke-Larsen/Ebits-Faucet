<?php
//PostBack for Offertoro
require_once ('Php/config.php');
require_once ('Php/mysqli.php');
$User = $_SESSION["UserID"]; //Grabbing userid from client
$resultfaucet = $con->query("select * from FaucetNew where PyrinUID='$User'"); //Used to check if the userid has a faucet userid
$row1 = $resultfaucet->fetch_array(MYSQLI_BOTH); //fetching information based on faucet table
$_SESSION["UseridFaucet"] = $row1['PyrinUID'];
$_SESSION["bitcoins"] = $row1['Balance'];
$_SESSION["ipaddress"] = $row1['Ipaddress'];
$_SESSION["CLAIM"] = $row1['Claim'];
$_SESSION["CLAIMT"] = $row1['CLAIMT'];
$_SESSION["popup"] = $row1['popup'];
$_SESSION["relink"] = $row1['relink'];
$bitcoinearly = $_SESSION["bitcoins"];
$claim = $_SESSION["CLAIM"];
$ipaddress = $_SESSION["ipaddress"];
$time = time();
$id = $_GET['id'];
$oid = $_GET['oid'];
$user_id = $_GET['user_id'];
$amount = $_GET['amount'];
$o_name = $_GET['o_name'];
$payout = $_GET['payout'];
$ip_address = $_GET['ip_address'];
$sig = $_GET['sig'];
$ipaddress = $_SERVER['REMOTE_ADDR'];
$check = md5($oid.'-'.$user_id.'-'.$OTSecret);

if($check == $sig){
    $amount =  ($OfferwallCut * $amount * $PricePerCoinUSD);
    $sql1 = $con->query("INSERT INTO `Offers` (`Time`, `UserID`, `Amount`, `Offerwall`, `Sig`) VALUES ('$curtime','$User','$amount','OfferToro','$sig')");
    $_SESSION["Token"] = $row1["Tokens"] +4;
    $_SESSION["Balance"] = $usdb1;
    $sql3 = $con->query(" UPDATE `User` set `Balance`=Balance+'$amount' , `Tokens`=Tokens+1 WHERE `UserID` = '$user_id' ");
    echo'1';
	}else{
		echo "Sorry buddy";
	}
?>
