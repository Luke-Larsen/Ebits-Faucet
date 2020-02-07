<?php
$User = $_SESSION["UserID"];
if(isset($User) && !empty($User)){
    if(!isset($_SESSION["CLAIM"])){
        //MAIN USER DATA
        $stmt = $con->prepare("select * from User where UserID=?");
        $stmt->bind_param("i", $User);
        $stmt->execute();
        $resultUser = $stmt->get_result();
        $row = mysqli_fetch_assoc($resultUser);
        //FAUCET DATA
        $stmt = $con->prepare("select * from FaucetClaim where UserID=?");
        $stmt->bind_param("i", $User);
        $stmt->execute();
        $resultFaucet = $stmt->get_result();
        $stmt->close();
        $row1 = mysqli_fetch_assoc($resultFaucet);
        //AUTO DATA
        $stmt = $con->prepare("select * from AutoClaims where UserID=?");
        $stmt->bind_param("i", $User);
        $stmt->execute();
        $resultFaucet = $stmt->get_result();
        $stmt->close();
        $row2 = mysqli_fetch_assoc($resultFaucet);
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
        $_SESSION["Balance"] = $row["Balance"];
        $_SESSION["WithdrawlAddress"] = $row1['WalletAddr'];
        //SETTING FROM FAUCET TABLE
        $_SESSION["LastClaimTime"] = $row1["Time"];        //Last Time a User claimed
        //SETTING FROM FAUCET TABLE
        $_SESSION["LastClaimTime"] = $row2["Time"];        //Last Time a User claimed
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
if(isset($refa)){
    $_SESSION["refid"] = $refa;
}
