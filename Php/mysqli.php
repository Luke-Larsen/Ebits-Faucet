<?php
//Enabling things for instance Mysqli (nothing needs to be changed below here)
if($DISABLEMysql == 0){
  $con = new mysqli($MysqlHost, $MysqlUser, $MysqlPassword, $MysqlDatabase);
}else{
  echo "Down";
}
?>
