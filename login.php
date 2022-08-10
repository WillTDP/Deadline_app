<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'deadline app');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<body>

<form action="login.php" method="post">

   <h2>LOGIN</h2>

   <?php if (isset($_GET['error'])) { ?>

       <p class="error"><?php echo $_GET['error']; ?></p>

   <?php } ?>

   <label>User Name</label>

   <input type="text" name="uname" placeholder="User Name"><br>

   <label>Password</label>

   <input type="password" name="password" placeholder="Password"><br> 

   <button type="submit">Login</button>

</form>

</body>
</body>
</html>