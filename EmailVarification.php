<?php
require_once ('Php/config.php');
require_once ('Php/mysqli.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '/PHPMailer/src/Exception.php';
require '/PHPMailer/src/PHPMailer.php';
require '/PHPMailer/src/SMTP.php';

$stmt = $con->prepare("select * from User where UserID=?");
$stmt->bind_param("i", $User);
$stmt->execute();
$resultUser = $stmt->get_result();
$row = mysqli_fetch_assoc($resultUser);
$Username = $row["Username"];
$email = $row["Email"];
if(!empty ($_GET['code'])){
    $getcode = $_GET['code'];
}else{
    $getcode = '';
}
$Emailhash = $row['emailHash'];
$EmailVarifyed = $row['emailVerified'];
if($getcode != ''&& $Emailhash != ''){
    if($getcode == $Emailhash){
        $_SESSION["UserID"] = $row['UserID'];
        $sqlSend1 = "UPDATE User SET emailHash = '', emailVerified = '1' WHERE UserID = $User";
        $sqlSend1 = $con->query($sqlSend1);
        echo "<script>window.alert('Success, Email Varified!'); window.location.replace('$URL');</script>";
    }
}
if(isset($_POST['Emailsubmit'])){
    //if(isset($_POST['g-recaptcha-response'])&& $_POST['g-recaptcha-response']){
        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $rsp  = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip$ip");
        $arr = json_decode($rsp,TRUE);
        //if($arr['success']){
            $code = $_POST['Email'];
            $hashCode = password_hash($code, PASSWORD_DEFAULT);
            $Emailhash = $row['emailHash'];
            if (password_verify($code, $Emailhash)) {
                $_SESSION["UserID"] = $row['UserID'];
                $sqlSend = "UPDATE User SET emailHash = '', emailVerified = '1' WHERE UserID = $User";
                $sqlSend = $con->query($sqlSend);
                echo "<script>window.alert('Success, Email Varified!'); window.location.replace('$URL');</script>";
                exit;
            }elseif(!password_verify($PW, $hash)){
                echo "<h4>Invalid code</h4>";
            }else{
                echo "<h4>An unexpected error has occured please try again later.</h4>";
            }
        //}else{
        //    echo "<h4>Wrong recaptica</h4>";
        //}
    //}else{
    //    echo "<h4>Please use the recaptica</h4>";
    //}
}
?>
<html>
    <body>
    <head>
        <title><?php echo $Title ?></title>
    </head>
    <?php
    if(!empty($User)){
        if($EmailVarifyed == 0){
    if($EmailVarifyed == 0 && $Emailhash == ''){
        //send email
        $activationcodebasic = rand(0, 9999);
        $activationcode = password_hash($activationcodebasic, PASSWORD_DEFAULT);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $emailDisplay = filter_var($email, FILTER_VALIDATE_EMAIL);
        $parts = explode('@',$email);
        if($parts[1] == 'gmail.com'){
            $email = str_replace('.', '', $parts[0]);
            $email = $email . '@' . $parts[1];
        }
        $mail = new PHPMailer(true);
        try {
            //Server settings
            //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();
            $mail->Host = $SMTPHost;
            $mail->SMTPAuth = true;
            $mail->Username = $SMTPUserName;
            $mail->Password = $SMTPPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $SMPTPort;
            //Recipients
            $mail->setFrom($EmailAccountName, 'Mailer');
            $mail->addAddress($email, $Username);
            //Content
            $mail->isHTML(true);
            $mail->Subject = "$URL Email Verification";
            $mail->Body    = "<html></body><div><div>Dear $username,</div></br></br>
                <div style='padding-top:8px;'>Please click The following link For verifying and activation of your account</div>
                <div style='padding-top:8px;'>Or enter this code on the email varification page: $activationcodebasic </div>
            <div style='padding-top:10px;'><a href='$HTTP://$URL/EmailVarification.php?code=$activationcode'>Click Here</a></div>
            </body></html>";
            $mail->send();
            echo 'Message has been sent';
            $SetActivationCode = "UPDATE User SET emailHash = '$activationcode' WHERE UserID = $User";
            $SetActivationCode = $con->query($SetActivationCode);
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        ?>
        <form action="" method="post" name="EmailForm" id="EmailForm">
        <div class="FormElement"> An email has been sent to <?php echo"$emailDisplay";?> please enter the code or follow the link.</div>
        <div class="FormElement">
        <input name="Email" required class="TField" id="Email" placeholder="Code" />
        <div class="g-recaptcha" data-sitekey="<?php echo $RecapSiteKey;?>"></div>
        <input name="Emailsubmit" type="submit" class="button" value="Submit">
      </div>
        <?php
    }else{
    ?>
    <form action="" method="post" name="EmailForm" id="EmailForm">
        <div class="FormElement"> Please Varify your email, Either enter in what was sent to your email or put in the code here. Please check your spam folder if you can't find the message.</div>
        <div class="FormElement">
        <input name="Email" required class="TField" id="Email" placeholder="Code">
        <div class="g-recaptcha" data-sitekey="<?php echo $RecapSiteKey;?>"></div>
        <input name="Emailsubmit" type="submit" class="button" value="Submit">
      </div>
    </form>
        <?php
    }
        }else{
            echo"<script>window.alert('Email Already Varified!'); window.location.replace('$URL');</script>";
        }
    }else{
        echo "Please login first";
    }
    ?>
    </body>
</html>
