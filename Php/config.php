<?php
//---------SHOW ERRORS-------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//Basic UserData
$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
$curtime = time();
if(isset($_SESSION["UserID"]) && !empty($_SESSION["UserID"])){
  $User = $_SESSION["UserID"];
}
//Site settings
$PriceApi = '';
$Title = 'Ebits Faucet';
$Name = 'Ebits Crypto';
$URL = '';
$HTTP = 'http'; //put in protocal http / https
$Version = 1;
$TimePerClaim = 360; //Time in seconds between claims
$AutoFaucetTimer = 360;
$EnableFaucet = true;
$EnableAutoFaucet = true;
$PricePerCoinOneUSD = 0.000001;
$MinumumWithdraw = 5;
$SideBarAd1 = '';
$SideBarAd2 = '';
$OTSecret = ''; //Offertoro Offerwall secret
$OTPublic = '';
$OfferwallCut = .5; //.5 means 50% of the revenue from the offerwall is profit if set properly with the websites.
$EmailAccountName = ''; //support@ebitsfaucet.com
$SMTPHost = '';
$SMTPUserName = '';
$SMTPPassword = '';
$SMPTPort = '587';
//FAUCET settings
$ClaimInUsdPerClick = .01;

//AUTO FAUCET SETTINGS
$ClaimInUsdPerClickAuto = .01;

//Express Crypto
$ECEnabled = true;
$ECSecret = ''; //API KEY
$ECWord = ''; //UserToken

//Mysqli Connections
$MysqlHost = "";
$MysqlUser = "";
$MysqlPassword = "";
$MysqlDatabase = "";
$DISABLEMysql = '0'; //Mainly used for oh shit moments when you need to disable mysqli

//-------VPS SETTINGS---------
$VPSUser = 'user';
$VPSPassword ='password';
$VPSHost = '127.0.0.1';
$VPSPort = '9901';//Port for communication of the RPC on the vps

//------recaptcha-----------
$secret = '';
$RecapSiteKey = '';
//------Tracking Code---------
//If you wish to use analytics code put it here:
//example:
//echo "<!-- Global Site Tag (gtag.js) - Google Analytics -->... (several lines of customized programming code appear here)</script>";
