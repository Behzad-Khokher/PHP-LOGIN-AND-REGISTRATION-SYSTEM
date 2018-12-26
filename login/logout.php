<?php include("functions/init.php");


 if(isset($_COOKIE['email'])){
     unset($_COOKIE['email']);
     
     setcookie('email','',time()-86400);
 }

session_destroy();

redirect("login.php");