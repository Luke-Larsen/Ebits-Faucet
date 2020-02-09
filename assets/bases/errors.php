<?php
if(isset($_GET['e'])&& $_GET['e'] != ''){
$ErrorCode = $_GET['e'];
  if($ErrorCode == 1){
  echo
  '
  <div style="position: absolute; bottom:3px; right:3px;">
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Express Crypto Error!</strong> The express crypto id is not right.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
';
  }
}
