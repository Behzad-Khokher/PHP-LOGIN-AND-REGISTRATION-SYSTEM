<?php 
include("includes/header.php");
password_reset();
?>
<h3>Recover Page</h3>

<form method="post">


         <div class="form-group">
              <label for="password">New Password</label>
              <input type="password" class="form-control" placeholder="Password" name="password">
          </div>

          <div class="form-group">
              <label for="confirm_password">Confirm New Password</label>
              <input type="password" class="form-control" placeholder="Password" name="confirm_password">
          </div>
    
    <input type="hidden" name="token" value="<?php echo token_generator(); ?>">  

  
  <button type="submit" class="btn btn-success">Reset Password</button>
        
 

</form>





<?php    
include("includes/footer.php");
?>

