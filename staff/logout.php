<?php
   session_start();
   unset($_SESSION["email"]);
   
   
   echo 'You have cleaned session';
   header('Refresh: 1; URL = index.php');
?>