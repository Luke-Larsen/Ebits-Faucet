<?php
//check for newest version
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/Luke-Larsen/Ebits-Faucet/releases/latest');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$data = curl_exec($ch);
curl_close($ch);
$array = json_decode($data, true);
$LastestVersion = $array['id'];
if($LastestVersion > $Version){
  $Update = TRUE;
}else{
  $Update = FALSE;
}
