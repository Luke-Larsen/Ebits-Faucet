<?php
session_start();
unset($_SESSION["UserID"]);
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(),'',0,'/');
session_regenerate_id();
header('location:index.php');
?>
