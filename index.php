<?php
// Initialize the session
session_start();

//connect to db
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'deadline app');
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// insert a quote if submit button is clicked
	if (isset($_POST['submit'])) {
		if (empty($_POST['name'])) {
			$errors = "You must fill in the task";
		}else{
			$name = $_POST['name'];
            $sql = "INSERT INTO lists" . "(name) " . "VALUES " . "('$name')";
            if ($mysqli→query($sql)) {
                printf("Record inserted successfully.<br />");
            };
            
            if ($mysqli→errno) {
                printf("Could not insert record into table: %s<br />", $mysqli→error);
            };			header('location: index.php');
		}
	}	


?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
    <div class="heading">
		<h2 style="font-style: 'Hervetica';">ToDo List Application PHP and MySQL database</h2>
	</div>
	<form method="post" action="index.php" class="input_form" >
        <?php if (isset($errors)) { ?>
	        <p><?php echo $errors; ?></p>
        <?php } ?>
		<input type="text" name="task" class="task_input">
		<button type="submit" name="submit" id="add_btn" class="add_btn">Add List</button>
	</form>
    <table>
	<thead>
		<tr>
			<th>N</th>
			<th>Tasks</th>
			<th style="width: 60px;">Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		// select all tasks if page is visited or refreshed
		$lists = mysqli_query($db, "SELECT * FROM lists");

		$i = 1; while ($row = mysqli_fetch_array($lists)) { ?>
			<tr>
				<td> <?php echo $i; ?> </td>
				<td class="name"> <?php echo $row['name']; ?> </td>
				<td class="delete"> 
					<a href="index.php?del_task=<?php echo $row['id'] ?>">x</a> 
				</td>
			</tr>
		<?php $i++; } ?>	
	</tbody>
</table>
</body>
</html>