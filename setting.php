<?php
include 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$var = $conn->query("select * from boxusers where pass = MD5('".$_POST['current_pass']."') AND username = '".
		$_SESSION['username']."';" ); 
	if( $var->num_rows == 1)
	{
		//Checking if passwords match and alert user if typos are present
		if(!($_POST['new_pass'] == $_POST['cnf_new_pass']))
	    {
		     echo "<script> alert('passwords do not match'); </script>";
	    }

	    else
	    {
		     echo "<script> alert('password updated'); </script>";
		     // updating DB with new password
		     $conn->query("update boxusers set pass =md5('".$_POST['new_pass']."') where username='".$_SESSION['username']."';");
		    echo"<script>";
		    echo" window.location='index.php';";
		    echo "</script>";
	    }

	}


	else
	{
		echo "<script> alert('wrong password. Try again'); </script>";
	}

	

}

?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="setting.css">
</head>

<body>

<!-- Header -->
<div id="topbar">
<a href="index.php" class="goHomeLink"><h1 id ='complaintHead'> THE COMPLAINT BOX </h1></a>
</div>

<!-- Change Password Form -->
<div id='form1' >
<h2> Change Password </h2>
<form  method='post' action="setting.php">
<div id='posi'>
<input class='field' type ='password' name ='current_pass' placeholder='current password' required> <br>
<input class='field' type ='password' name ='new_pass' placeholder='new password' required> <br>
<input class='field' type ='password' name ='cnf_new_pass' placeholder='confirm new password' required> <br>
<input id='change' type ='submit' name ='submit' value ='Change'>
</div>
</form> 

</div>

</body>
</html>