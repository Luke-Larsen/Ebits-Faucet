<?php
if($EnableAutoFaucet != true){
  header('location:404.php');
}
if(isset($_POST['Claim'])){
  $stmt = $con->prepare("select * from faucetAutoClaims where `UserID`=?");
  $stmt->bind_param("s", $User,);
  $stmt->execute();
  $results = $stmt->get_result();
  $stmt->close();
  $row = mysqli_fetch_assoc($results);
  if($TimePerClaim >= ($curtime - $row[claimTime]){
    echo "You can claim again!"
    //Claim stuff
  }else{
    $TimeWait = ($curtime - $row[claimTime]) -$TimePerClaim;
    echo "Please wait $TimeWait seconds";
  }
}
