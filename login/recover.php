<?php include("includes/header.php");

display_message();
recover_password();
?>
<h3>RECOVER PAGE</h3>



<form method="post">

    <div class="form-group">
      <label for="exampleInputEmail1"><strong>Email Address</strong></label>
      <input type="email" class="form-control" placeholder="Enter email" name="email">
        <input type="hidden" name="token" value="<?php echo token_generator(); ?>">  
    </div>
    <button type="submit" class="btn btn-danger">Recover</button>

</form>





<?php display_inbox(); ?>



<?php include("includes/footer.php"); ?>