<?php
$User = $_SESSION["UserID"];
$CurrentTime = time();
$ipAddress = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
if(isset($User) && !empty($User)){
    if(!isset($_SESSION["CLAIM"])){
        //MAIN USER DATA
        $stmt = $con->prepare("select * from user where UserID=?");
        $stmt->bind_param("i", $User);
        $stmt->execute();
        $resultUser = $stmt->get_result();
        $row = mysqli_fetch_assoc($resultUser);
        //FAUCET DATA
        $stmt = $con->prepare("select * from FaucetNew where PyrinUID=?");
        $stmt->bind_param("i", $User);
        $stmt->execute();
        $resultFaucet = $stmt->get_result();
        $stmt->close();
        $row1 = mysqli_fetch_assoc($resultFaucet);
        //IP DATA
        if($ipAddress != $row['ip']){
            $stmt = $con->prepare("UPDATE `user` SET `iptime`=?, `ip`=? WHERE `UserID` = ?");
            $stmt->bind_param("isi", $CurrentTime, $ipAddress, $User);
            $stmt->execute();
            $resultBannedIPData = $stmt->get_result();
            $stmt->close();
        }
        $stmt = $con->prepare("SELECT `Reason` FROM `BannedIp` WHERE `Ip` = ?");
        $stmt->bind_param("s", $ipAddress);
        $stmt->execute();
        $resultBannedIPData = $stmt->get_result();
        $stmt->close();
        if(mysqli_num_rows($resultBannedIPData)!=0){
            $_SESSION['IpBan'] = true;
        }
        //SETTING VALUES
        //SETTING FROM USER TABLE
        $_SESSION["Username"] = $row['Username'];
        $_SESSION["UserLevel"] = $row['UserLevel'];
        $_SESSION["FirstName"] = $row['Fname'];
        $_SESSION["LastName"] = $row['Lname'];
        $_SESSION["Email"] = $row['Email'];
        $_SESSION["PW"] = $row['Password'];
        $_SESSION["DarkModeShow"] = $row["Darkmode"];
        //SETTING FROM FAUCET TABLE
        $_SESSION["Balance"] = $row1["Balance"];
        $_SESSION["WithdrawlAddress"] = $row1['Address'];
        $_SESSION["ExpressCryptoID"] = $row1['ExpressCryptoId'];
        $_SESSION["Captcha"] = $row1["Captcha"];    //What Type of Captcha
        $_SESSION["CLAIMT"] = $row1["CLAIMT"];      //Claim Times
        $_SESSION["CLAIM"] = $row1["Claim"];        //Last Time a User claimed
        $_SESSION["relink"] = $row1['relink'];      //Redirect Links
        $_SESSION["PopUp"] = $row1['popup'];
        $_SESSION["JSE"] = $row1['JSE'];
        $_SESSION["Token"] = $row1['Tokens'];
    }
}
//RUNS ON EVERY PAGE, BE SPARING

//Generalized User Settings Checks
//BAN STUFF
if(isset($_SESSION['IpBan']) && $_SESSION['IpBan'] == true){
    header("location:/error/BannedIp.html");
}
if (isset($_SESSION["UserID"]) && $_SESSION["UserLevel"] == 0) {
    header("location:/error/Banned.html");
}
//MATENANCE OR ERRORS
if ($_SESSION["UserLevel"] < 3) {
    if ($mat == 'T') {
        header("location:/error/Matenence.php");
    }
}
