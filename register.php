<?php
// Include config file
require_once "server.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_error = $password_error = $confirm_password_error = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty($_POST["username"])){
        $username_error = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($statement = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($statement, "s", $param_username);
            
            // Set parameters
            $param_username = $_POST["username"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($statement)){
                /* store result */
                mysqli_stmt_store_result($statement);
                
                if(mysqli_stmt_num_rows($statement) == 1){
                    $username_error = "This username is already taken.";
                } else{
                    $username = $_POST["username"];
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($statement);
        }
    }
    
    // Validate password
    if(empty($_POST["password"])){
        $password_error = "Please enter a password.";     
    } else{
        $password = $_POST["password"]  ;
    }
    
    // Check input errors before inserting in database
    if(empty($username_error) && empty($password_error) && empty($confirm_password_error)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($statement = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($statement, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_BCRYPT, ); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($statement)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($statement);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body class="site">
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form">
                <label>Username:</label>
                <input class="inpt" type="text" name="username"<?php echo (!empty($username_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_error; ?></span>
            </div>    
            <div class="form">
                <label>Password:</label>
                <input class="inpt" type="password" name="password" <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_error; ?></span>
            </div>
            <div class="form">
                <input class="login" type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Already have an account? <a class="link" href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>