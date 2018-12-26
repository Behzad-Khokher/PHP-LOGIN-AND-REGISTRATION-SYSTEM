<?php


/*
    Helper Functions
*/


// Cleans htmlentities
function clean($string){

    return htmlentities($string);
}

// Redirects To a new page
function redirect($location){

    return header("Location: {$location}");
}

// Sets session message
function set_message($message){

    if (!empty($message)) {
        $_SESSION['message'] = $message;

    } else {
        $_SESSION['message'] = "";
    }

}

// Displays session message and unsets session message
function display_message(){

    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
     }
}

// Generate random value
function token_generator(){

    $token  = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    return $token;
}

// Will Diplay Error formated in CSS(Bootstrap)
function display_validation_error($error){
    
    $error_message = '<div class="alert alert-warning" role="alert"><strong>Warning!</strong> '.$error.'</div>';
    echo $error_message;
}

// Will Return Error formated in CSS(Bootstrap)
function get_validation_error($error){
    
    $error_message = '<div class="alert alert-warning" role="alert"><strong>Warning!</strong> '.$error.'</div>';
    return $error_message;
}

function username_exists($user_name){
    
    $sql = "SELECT id FROM users WHERE username = '$user_name'";
    $result = query($sql);
    

    if(row_count($result) == 1){
        return true;
    } else {
        return false;
    }
} 

function email_exists($email){
    
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = query($sql);
    
    
    if(row_count($result) == 1){
        return true;
    } else {
        return false;
    }
} 

function send_mail($email, $subject, $msg, $headers){
    
    return mail($email, $subject, $msg, $headers);
}


function set_inbox($message){
    
    $_SESSION['inbox'] = $message;

}

function display_inbox( ){
    
    echo "<hr><h3>Inbox: </h3>";
    if (isset($_SESSION['inbox'])){
        if ($_SESSION['inbox'] != ""){
            
            echo "<p>".$_SESSION['inbox']."</p>";
            unset($_SESSION['inbox']);
        } else {
            echo "<p>No mail</p>";
        }
    
    }
    echo "<hr>";
    
}


/********Validation Functions********/


function validate_user_data(){
    
    
    $errors = [];

    $min = 3;
    $max = 20;

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $first_name = clean($_POST['first_name']);
        $last_name  = clean($_POST['last_name']);
        $user_name  = clean($_POST['user_name']);
        $email      = clean($_POST['email']);
        $password   = clean($_POST['password']);
        $confirm_password   = clean($_POST['confirm_password']);


        if(strlen($first_name) < $min){
            $errors[] = "Your First Name can not be less than {$min} characters";
        }

        if(strlen($first_name) > $max){
            $errors[] = "Your First Name can not be more than {$max} characters";
        }

        if(strlen($last_name) < $min){
            $errors[] = "Your Last Name can not be less than {$min} characters";
        }

        if(strlen($last_name) > $max){
            $errors[] = "Your Last Name can not be more than {$max} characters";
        }

        if(strlen($user_name) < $min){
            $errors[] = "Your User Name can not be less than {$min} characters";
        }

        if(strlen($user_name) > $max){
            $errors[] = "Your User Name can not be more than {$max} characters";
        }
        
        if(username_exists($user_name)){
            $errors[] = "Your Username  is Already registered ";
        }
        
        if(email_exists($email)){
            $errors[] = "Your Email is Already registered ";
        }
        
        if(strlen($email) < $min){
            $errors[] = "Your Email can not be less than {$min} characters";
        }
        
        if($password != $confirm_password){
            $errors[] = "Your Passwords do not match";
        }

        if(!empty($errors)){
            foreach ($errors as $error) {
                display_validation_error($error);
            }
        } else{
            
            if(register_user($first_name, $last_name, $user_name, $email,$password)){
                 
                set_message("<p class='bg-success text-center'>Please check your email for an activation link.</p>");
                
                
            //    redirect("index.php");
            } else {
                
                set_message("<p class='bg-success text-center'>Sorry We could not register.</p>");
            }

        }
    }
}

function register_user($first_name, $last_name, $user_name, $email,$password){

    $first_name = escape($first_name);
    $last_name  = escape($last_name);
    $user_name  = escape($user_name);
    $email      = escape($email);
    $password   = escape($password);
    
    if(email_exists($email)){
        return false;
        
      } else if (username_exists($user_name)){
        return false;
        
    } else {
        $password = md5($password);
        
        $validation_code = md5($user_name . (string)(microtime())); 
        
        $sql = "INSERT INTO users(first_name, last_name, username, email, password, validation_code,active)";
         
        $sql.= " VALUES ('$first_name', '$last_name', '$user_name', '$email', '$password', '$validation_code', 0)";
        $result = query($sql);
        confirm($result);
        
        $subject = "Activate Account";
        $msg = " Please Click the link below to activate your Account
        http://localhost/login/activate.php?email=$email&code=$validation_code
        ";
        
        $headers = "From: noreply@yourwebsite.com";
        
        
//        send_mail($email, $subject, $msg, $headers);
        set_inbox($msg);
        
        
        return true;
    }
}



function activate_user() {
    
    if($_SERVER['REQUEST_METHOD'] == "GET") {
        
        if(isset($_GET['email'])) {
            
            $email = clean($_GET['email']);
            
            $validation_code = clean($_GET['code']);
            
            $sql = "SELECT id FROM users WHERE email = '".escape($_GET['email'])."' AND validation_code = '".escape($_GET['code'])."' ";
            
            $result = query($sql);
            confirm($result);
            
            if(row_count($result) == 1){
                $sql2 = "UPDATE users SET active = 1, validation_code = '0' WHERE email = '".escape($email). "' AND validation_code = '".escape($validation_code). "' ";
                
                $result2 = query($sql2);
                confirm($result2); 
                
                set_message("<p class='bg-success'>Your Account has been activated. Please Login.</p>");
                
                redirect("login.php");

                
            }else{
                set_message("<p class='bg-danger'>Sorry Your Account has not been activated. Please Login.</p>");
                
                redirect("login.php");
            }
            
            
            
        }
    }
}



function validate_login_data(){

    $errors = [];

    $min = 3;
    $max = 20;

    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        
        $email      = clean($_POST['email']);
        $password   = clean($_POST['password']);
        $remember   = isset($_POST['remember']);
        
        
        
        
        if(empty($email)) {
            
            $errors[] = "Email field cannot be empty";
        }
        
        
        if(!empty($errors)){
            foreach ($errors as $error) {
                display_validation_error($error);
            }
        } else {
            
            if(login_user($email, $password, $remember)){
                
                redirect("admin.php");
                
            } else {
                display_validation_error("Your Credentials are not correct");
            }
        }
        
        

    
    }
}



function login_user($email,$password,$remember){
    
    $sql = "SELECT password FROM users WHERE email = '".escape($email)."' AND active = 1";
    $result = query($sql);

    if(row_count($result) == 1) {
        
        $row = fetch_array($result);
        
        $db_password = $row['password'];
        
        if (md5($password) == $db_password){
            
            if ($remember == "on"){
                setcookie('email', $email, time() + 86400);
            }
            
            $_SESSION['email'] = $email;
            
            return true;
        } else {
            
            return false;
        }
    } else {
        
        return false;
    }
    
}


function logged_in(){
    
    if (isset($_SESSION['email']) || isset($_COOKIE['email']) ){
        return true;
    } else {
        
        redirect("index.php");
        return false;
    }
}





function recover_password(){
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){ 
            
         $email = clean($_POST['email']);
        
         if ( isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
             
              if (email_exists($email)){
                  
                  $validation_code = md5($email . (string)microtime());
                  setcookie('temp_access_code', $validation_code, time()+60);
                  
                  $sql = "UPDATE users SET validation_code = '".escape($validation_code)."' WHERE email = '".escape($email)."'";
                  $result = query($sql);
                  confirm($result);
                  
                  $message = "Please <a href='http://localhost/login/code.php?email=$email&validation_code=$validation_code'>Click Here</a> to reset password.Validation Code=$validation_code
                  ";
                  
                  $headers = "From: noreply@yourwebsite.com";
                  $subject = "Your Recovery Code";
                  

                  set_inbox($message);
                  
/*                if (send_mail($email, $subject, $message, $headers)) {
                      
                  } else {
                      echo display_validation_error("Email can not be sent");
                  }*/
                  
                  
              } else {
                  echo display_validation_error("This email does not exists");
              }
             
         } else {
             redirect("index.php");
         }
    
        
        
    }
    
}


function validate_code () {
    
    if(isset($_COOKIE['temp_access_code'])) {
        
            if(!isset($_GET['email']) && !isset($_GET['code'])){
                
                redirect("index.php");
            } else if (empty($_GET['email']) || empty($_GET['validation_code'])){
                
                redirect("index.php");
            } 
            else {
                if(isset($_POST['code'])){
                    $email = clean($_GET['email']);
                    $validation_code = clean($_POST['code']);
                    $sql = "SELECT id FROM users WHERE validation_code= '".escape($validation_code)."' AND email = '".escape($email)."'";
                    $result = query($sql);
                    
                    if(row_count($result) == 1){
                        
                        setcookie('temp_access_code', $validation_code, time()+300);
                        
                        redirect("reset.php?email=$email&code=$validation_code");
                    } else {
                        display_validation_error("Sorry wrong validation code");
                    }
                }
            }
        
    } else {
        set_message("<p class='bg-danger>Sorry your validation was expired.</p>");
        redirect("recover.php");
    }
}


function password_reset(){
    
    if(isset($_GET['email']) && isset($_GET['code']) && !empty($_GET['email']) && !empty($_GET['code'])){
        if(isset($_COOKIE['temp_access_code'])){
            
            if(isset($_SESSION['token']) &&  isset($_POST['token'])){
            
            if ($_POST['token'] == $_SESSION['token']) {
                
                if($_POST['password'] == $_POST['confirm_password']){
                    
                    $updated_password = md5($_POST['password']);
                    
                    $sql = "UPDATE users SET password = '".escape($updated_password)."' WHERE email ='".escape($_GET['email'])."'";
                    query($sql);
                    set_message('<p class="bg-success">Your password has been updated</p>');
                    redirect("login.php");
                } else {
                    display_validation_error("Your Passwords don't match");
                }
            }
        }
    }
        
    } else {
        set_message("<p class='bg-danger'>Sorry your time has expired</p>");
        redirect("recover.php");
    }
}



?>

