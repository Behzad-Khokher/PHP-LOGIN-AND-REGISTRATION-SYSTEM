<?php 
include("includes/header.php");
    
display_message();
validate_login_data();


if (isset($_SESSION['email']) || isset($_COOKIE['email']) ){
    echo "<p class='bg-success'>Your Account has been activated. Please Login.</p>";
}
?>
<h3>LOGIN PAGE</h3>
<form method="post">

<!--  
  <div class="form-group">
      <label for="user_name">User Name</label>
      <input type="text" class="form-control" placeholder="First Name" name="user_name">
  </div>
-->

    <div class="form-group">
      <label for="exampleInputEmail1">Email Address</label>
      <input type="email" class="form-control" placeholder="Enter email" name="email">
    </div>

    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" placeholder="Password" name="password">
    </div>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="gridCheck" name="remember">
        <label class="form-check-label" for="gridCheck">
            Remember Me
        </label>
    </div>

  <button type="submit" class="btn btn-primary">Login</button>
        
 

</form>





<?php    
include("includes/footer.php");
?>

