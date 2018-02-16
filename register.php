<?php

$conn = new mysqli("localhost","root","","mydb");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	if(validateUsername($_POST['username']) && checkUser($_POST['username']) && checkPasswords($_POST['password1'],$_POST['password2']))
	{
		
		$sql = "INSERT INTO register_users values (?,MD5(?))";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('ss',$username,$password);
		
		$username = $_POST['username'];
		$password = $_POST['password1'];
		
		$stmt->execute();
		
		echo "<script>alert('Your request wii be processed shortly')</script>";
	}
}

function validateUsername($username)
{
	if(!preg_match("/^[a-zA-Z0-9_]*$/",$username)) 
	{
		echo "<script>alert('Enter a valid username.\\nUsername should contain only letters numbers and underscores')</script>";
		return false;
	}
	else return true;
}

function checkUser($username)
{
	global $conn;
	
	$sql = "SELECT * FROM  boxusers WHERE username=?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s",$username);
	$stmt->execute();
	
	if($stmt->fetch())
	{
		echo "<script>alert('Username already taken')</script>";
		return false;
	}
	
	else return true;
}

function checkPasswords($password1, $password2)
{
	if($password1 === $password2) return true;
	else
	{
		echo "<script>alert('Passwords do not match')</script>";
		return false;
	}
}

?>

<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" type="text/css" href="register.css">
</head>

<body>

<!-- Header -->
<h1>THE COMPLAINT BOX</h1>
</body>

<!-- Registration form -->
<form class="registerForm" action="register.php" method="POST">
<h2> Register </h2>
<input type="text" placeholder="Username" name="username" required><br><br>
<input type="password" placeholder="Password" name="password1" required><br><br>
<input type="password" placeholder="Confirm Password" name="password2" required><br>
<input type="submit" value="Join"><br><br>
<a href="login.php">Go to Login</a>

</form>

</html>