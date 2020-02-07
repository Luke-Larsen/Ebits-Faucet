<?php
require_once ('Php/config.php');
require_once ('Php/mysqli.php');

$getemail = $_POST['partialUseremail'];
$parts = explode('@',$getemail);
if(strtolower($parts[1]) == 'gmail.com'){
    $getemail = str_replace('.', '', $parts[0]);
    $getemail = $getemail . '@' . $parts[1];
}
$result1 = $con->query("select Email from User where Email = '$getemail'");
while($userarray1 = mysqli_fetch_array($result1)) {
    if ($userarray1 = $getemail) {
        echo "Email Used";
    }
}
?>
