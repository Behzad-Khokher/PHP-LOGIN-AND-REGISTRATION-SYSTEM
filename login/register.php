
<?php
include("includes/header.php");
validate_user_data();
display_message();
 ?>

<h3>REGISTERATION PAGE</h3>
      <form method="post">
          <div class="form-group">
              <label for="first_name">First Name</label>
              <input type="text" class="form-control" placeholder="First Name" name="first_name">
          </div>

          <div class="form-group">
              <label for="last_name">Last Name</label>
              <input type="text" class="form-control" placeholder="First Name" name="last_name">
          </div>

          <div class="form-group">
              <label for="user_name">User Name</label>
              <input type="text" class="form-control" placeholder="First Name" name="user_name">
          </div>

          <div class="form-group">
              <label for="exampleInputEmail1">Email Address</label>
              <input type="email" class="form-control" placeholder="Enter email" name="email">
              <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
          </div>

          <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" placeholder="Password" name="password">
          </div>

          <div class="form-group">
              <label for="confirm_password">Confirm Password</label>
              <input type="password" class="form-control" placeholder="Password" name="confirm_password">
          </div>

          <button type="submit" class="btn btn-primary">Submit</button>

      </form>


<?php 
display_inbox();
include("includes/footer.php"); ?>