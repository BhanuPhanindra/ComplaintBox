<?php
session_start();
$_SESSION['logged'] = NULL;	
$_SESSION['username'] = NULL;
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="topbar">
<h1 id ='complaintHead'> THE COMPLAINT BOX </h1>
</div>

<div id="form">
<h2>It's Ok To Complain Here</h2><br>
<h1 class="heading">Login</h1>
<br>
<form name="loginform" action="login.php" method="POST" >
<input type="text" name="username" placeholder="Username" required></input><br><br>
<input type="password" name="password" placeholder="Password" required></input><br><br>
<input type="submit" value=">>"></input><br><br>
<a href="register.php" style="color:white; font-family: arial; text-decoration:none">Register</a>
</form>
</div>


<?php

//connect to database
$conn = new mysqli("localhost","root","","mydb");

if($conn->connect_error) echo "<script>alert('Error Connecting')</script>";
else; //echo "<script>alert('Connected to Database')</script>";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(!validate_username($_POST["username"]) === TRUE)
		{
			echo "<script> alert('Username should contain only letters, numbers and underscores'); </script>";
			exit;
		}
	
		
		
		$user = $_POST['username'];

		
		//SQL query to search for username and password, BINARY used to make it case sensitive
		//echo "SELECT * FROM boxusers WHERE BINARY username ='".$_POST["username"]."' AND pass = MD5('".$_POST["password"]."');";
		$sql = "SELECT * FROM boxusers WHERE BINARY username = ? AND pass = MD5(?);";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('ss',$username,$password);
		$username = $_POST['username'];
		$password = $_POST['password'];
			
		$stmt->execute();
		
		
		//We will have exactly one row if there is a valid user
		if($stmt->fetch())
		{
			$_SESSION['logged'] = 'yes';
			$_SESSION['username'] = $user;
			echo "<script>window.location ='index.php';</script>";
		}
		
		//We will have zero rows if no such user
		else echo "<script>alert('Invalid username or password')</script>";
	}
	
	else ;
	
	
	//Function to check username for errors
	function validate_username($string)
	{
		/*$string = trim($string);
		$string = stripslashes($string);
		$string = htmlspecialchars($string);
		*/
		
		//$length = strlen($string);
		
	//Server side validation of username	
		if (!preg_match("/^[a-zA-Z0-9_]*$/",$string)) 
		{ 
			return FALSE;
		}
		
		
		else return TRUE;
	}

?>
</body>
</html>