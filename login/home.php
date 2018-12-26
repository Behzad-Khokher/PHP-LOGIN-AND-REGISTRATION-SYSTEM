<?php

include("includes/header.php");
    
if (isset($_SESSION['email']) || isset($_COOKIE['email']) ){
    
    echo "<p class='bg-success'>Your Logged In</p>";
} else {
    echo "<p class='bg-danger'>Your Logged Out. Please Login or Register</p>";
}

?>

<h3>Home PAGE</h3>



<?php
include("includes/footer.php");