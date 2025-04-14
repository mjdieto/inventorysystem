<?php
  require_once('includes/load.php');
  if(!$session->logout()) {redirect("login_v2.php");}
?>
