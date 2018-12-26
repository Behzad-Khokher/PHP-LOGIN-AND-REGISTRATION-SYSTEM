<?php include("includes/header.php");


display_message();
validate_code();

?>
<h3>CODE PAGE</h3>


<form method="post">

    <div class="form-group">
      <label for="code">Enter Code</label>
      <input type="password" class="form-control" placeholder="Code" name="code">
    </div>

  <button type="submit" class="btn btn-primary">Reset Password</button>
  <input type="hidden" name="token" value="<?php echo token_generator(); ?>">        
 

</form>






<?php include("includes/footer.php"); ?>