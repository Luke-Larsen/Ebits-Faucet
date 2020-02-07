<?php
require_once 'Php/config.php';
require_once 'Php/mysqli.php';

if(isset($User)&&$User != ''){
}else{
  header('location:index.php');
}
?>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title><?php echo $Title ?> Account</title>
</head>
<?php include_once "assets/bases/Nav.php";?>
<h3 style='text-align:center;'>Welcome</h3>
<?php include_once "assets/bases/footer.php";?>
</html>
