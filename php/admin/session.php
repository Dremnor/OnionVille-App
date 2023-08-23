<?php
   include_once('/php/includes/debug.php');
   session_start();
   include_once('/php/includes/utils.php'); 
   include_once('/php/checks/admin_check.php');
  
?>



<?php
   echo '<pre>';
   var_dump($_SESSION);
   echo '</pre>';
