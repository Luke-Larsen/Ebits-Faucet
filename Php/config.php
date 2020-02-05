<?php
session_start();
//Basic UserData
$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
$curtime = time();

//Site settings
$Title = 'Ebits Faucet';
$URL = '';
$HTTP = 'http'; //put in protocal http / https
$Version = 1;

//Mysqli Connections
$MysqlHost = "localhost";
$MysqlUser = "user";
$MysqlPassword = "";
$MysqlHost = ""
$DISABLEMysql = '0'; //Mainly used for oh shit moments when you need to disable mysqli

//---------SHOW ERRORS-------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//-------VPS SETTINGS---------
$VPSUser = 'user';
$VPSPassword ='password';
$VPSHost = '127.0.0.1';
$VPSPort = '9901';//Port for communication of the RPC on the vps

//------recaptcha-----------
$secret = '';

//------Tracking Code---------
//If you wish to use analytics code put it here:
//example:
//echo "<!-- Global Site Tag (gtag.js) - Google Analytics -->... (several lines of customized programming code appear here)</script>";
