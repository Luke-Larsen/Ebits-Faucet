<nav class="navbar navbar-expand-lg navbar-light" style="background-color:#D3D3D3">
  <a class="navbar-brand" href="index.php"><img src="assets/img/logo.png" alt="logo" style='height:100%; display:inline-block;'/> <?php echo $Title; ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php if(basename($_SERVER['PHP_SELF'])== "faucet.php"){echo'active';}?> ">
        <a class="nav-link " href="faucet.php">Faucet</a>
      </li>
      <li class="nav-item <?php if(basename($_SERVER['PHP_SELF'])== "Auto.php"){echo'active';}?> ">
        <a class="nav-link" href="Auto.php">Auto Faucet</a>
      </li>
      <li class="nav-item <?php if(basename($_SERVER['PHP_SELF'])== "offerwalls.php"){echo'active';}?> ">
        <a class="nav-link" href="offerwalls.php">Offerwalls</a>
      </li>
      <li class="nav-item <?php if(basename($_SERVER['PHP_SELF'])== "withdraw.php"){echo'active';}?> ">
        <a class="nav-link" href="withdraw.php">Withdraw</a>
      </li>
      <li class="nav-item <?php if(basename($_SERVER['PHP_SELF'])== "logout.php"){echo'active';}?> ">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>
<br>
