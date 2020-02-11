<?php
session_start();
unset($_SESSION["UserID"]);
session_unset();
session_destroy();
setcookie(session_name(),'',0,'/');
session_write_close();
echo"<script>window.location = '/index.php';</script>";
?>
