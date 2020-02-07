<?php
require_once ('Php/config.php');
require_once ('Php/mysqli.php');
if($EnableAutoFaucet != true){
  header('location:404.php');
}

?>
<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title><?php echo $Title ?> Auto Faucet</title>
  </head>
  <body>
    <?php include_once "assets/bases/Nav.php";?>
    <div class='row'>
      <div class='col-sm-2' style="background-color:blue;height:100%">
        <?php echo $SideBarAd1; ?>
      </div>
      <div class='col-sm-8'>
        <div>
          <!-- Put banner ads in these places -->
        </div>
        <div>

        </div>
        <div>
<!-- Put banner ads in these places -->
        </div>
      </div>
      <div class='col-sm-2' style="background-color:red;height:100%">
        <?php echo $SideBarAd2; ?>
      </div>
    </div>
    <?php include_once "assets/bases/footer.php";?>
  </body>
</html>
