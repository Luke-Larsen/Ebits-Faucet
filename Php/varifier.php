<?php
require_once ('Php/config.php');
require_once ('Php/mysqli.php');
$partialusername = $_POST['partialUsername'];

$result = $con->query("select Username from User where Username = '$partialusername'");
while($userarray = mysqli_fetch_array($result)) {
    if ($userarray = $partialusername) {
        echo "Username Taken";
    }
}
?>
