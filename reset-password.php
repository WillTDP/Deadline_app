<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "server.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_error = $confirm_password_error = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_error = "Please enter the new password.";     
    }
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_error = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_error) && ($new_password != $confirm_password)){
            $confirm_password_error = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_error) && empty($confirm_password_error)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($statement = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($statement, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($password, PASSWORD_BCRYPT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($statement)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($statement);
        }
    }
    
    // Close connection
    mysqli_close($link);

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="loginstyle.css">

</head>
<body class="site">
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form">
                <label>New Password</label>
                <input class="inpt" type="password" name="new_password" <?php echo (!empty($new_password_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_error; ?></span>
            </div>
            <div class="form">
                <label>Confirm Password</label>
                <input class="inpt" type="password" name="confirm_password" <?php echo (!empty($confirm_password_error)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_error; ?></span>
            </div>
            <div class="form">
                <input class="login" type="submit" value="Submit">
                <a class="link" href="index.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>