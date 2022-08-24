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
// delete task
if (isset($_GET['del_task'])) {
	$id = $_GET['del_task'];

	mysqli_query($link, "DELETE FROM lists WHERE id=".$id);
	header('location: index.php');
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="indexstyle.css">
    <style>
        /*body{ font: 14px sans-serif; text-align: center; }*/
    </style>
</head>
<body class="site">
	<div class="wlcm">
    <h1 class="Welcome">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <p class="account">
        <a href="reset-password.php" class="btn">Reset Your password</a>
        <a href="logout.php" class="btn">Sign Out of Your Account</a>
    </p>
	</div>
	<div class="list">
    	<div class="title">
			<h2 style="font-style: 'Hervetica';">What's your ToDo?</h2>
		</div>
		<div class="Input_f">
			<form method="post" action="index.php" class="Input_r">
        		<?php if (isset($errors)) { ?>
	        		<p><?php echo $errors; ?></p>
        		<?php } ?>
				<div class="input_e">
					<input type="text" name="name" class="input">
					<input type="text" name="description" class="input">
				</div>
				<div class="btn_e">
					<button type="submit" name="submit" id="add_btn" class="btn2">Add List</button>
				</div>
			</form>
		</div>
    	<table>
		<thead>
			<tr class="things">
				<th>N</th>
				<th>Tasks</th>
				<th>Description</th>
				<th style="width: 60px;">Action</th>
			</tr>
		</thead>

		<tbody>
		<?php
            // select all tasks if page is visited or refreshed
			require_once "server.php";
            $lists = mysqli_query($link, "SELECT * FROM lists");

            while ($row = $lists->fetch_assoc()) {
        ?>
            <tr class="things">
                <td><?php echo $row['id'] ?></td>
                <td class="name"><?php echo $row['name']; ?></td>
				<td class="description"><?php echo $row['Description']; ?></td>
                <td class="delete">
                    <a href="index.php?del_task=<?php echo $row['id'] ?>">x</a>
                </td>
            </tr>
        <?php } ?>
		</tbody>
		</table>
	</div>
</body>
</html>