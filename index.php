<?php
// Initialize the session
session_start();

//connect to db
require_once "server.php";


// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// insert a quote if submit button is clicked
// Define variables and initialize with empty values
$name = $Description = "";
$name_error = $Description_error = $list_error = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$name = trim($_POST['name']);
    $Description = trim($_POST['description']);
    $name_error = "";
    $Description_error = "";
    $list_error = "";
    $validated = true;

	// Validate name
	if(empty($name)){
    	$name_err = "Please enter a name.";
    	$validated = false;
	}

	// Validate description
		if(empty($Description)){
    		$Description_err = "Please enter a description.";
    		$validated = false;
	}
	if($validated) {
    	// Handle adding entry to DB
		$sql = "INSERT INTO lists (name, Description) VALUES (?, ?)";

	if($statement = mysqli_prepare($link, $sql)){
    	// Bind variables to the prepared statement as parameters
    	mysqli_stmt_bind_param($statement, "ss", $name, $Description);

    	// Attempt to execute the prepared statement
    	if(!mysqli_stmt_execute($statement)){
        	echo "Oops! Something went wrong. Please try again later.";
    	}

    	// Close statement
    	mysqli_stmt_close($statement);
	}
	}else{
    	// Display errors
    	echo $name_err;
    	echo "<br />";
    	echo $Description_err;
}
}


// Close connection
mysqli_close($link);



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
        <a href="reset-password.php" class="btn btn-warning">Reset Your password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
    <div class="heading">
		<h2 style="font-style: 'Hervetica';">ToDo List Application PHP and MySQL database</h2>
	</div>
	<form method="post" action="index.php" class="input_form" >
        <?php if (isset($errors)) { ?>
	        <p><?php echo $errors; ?></p>
        <?php } ?>
		<input type="text" name="name" class="task_input">
		<input type="text" name="description" class="description_input">
		<button type="submit" name="submit" id="add_btn" class="add_btn">Add List</button>
	</form>
    <table>
	<thead>
		<tr>
			<th>N</th>
			<th>Tasks</th>
			<th>Description</th>
			<th style="width: 60px;">Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		// select all tasks if page is visited or refreshed
		$lists = mysqli_query($db, "SELECT * FROM 'lists'");

		$i = 1; while ($row = mysqli_fetch_array($lists)) { ?>
			<tr>
				<td> <?php echo $i; ?> </td>
				<td class="name"> <?php echo $row['name']; ?> </td>
				<td class="Description"> <?php echo $row['Description']; ?> </td>
				<td class="delete"> 
					<a href="index.php?del_task=<?php echo $row['id'] ?>">x</a> 
				</td>
			</tr>
		<?php $i++; } ?>	
	</tbody>
</table>
</body>
</html>