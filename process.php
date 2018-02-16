

<?php
include 'config.php'; 

if($_SESSION['username'] !== 'admin')
{
	echo "<script> alert('Acess Denied'); </script>";
	echo "<script> window.location='login.php'; </script>";
}

$x = $_GET['x'];

if(isset($_GET['username']))
{	
$username = $_GET['username'];
$sql = $conn->query("select pass from register_users where username ='$username';");
$pass = $sql->fetch_assoc();
}
if($x==1)
{
	$conn->query("insert into boxusers values ('$username','".$pass['pass']."');");
	$conn->query("delete from register_users where username='$username';");
	echo " <script> window.location='registerSetting.php'; </script>";
}

 elseif($x==2)
{
	$conn->query("delete from register_users where username='$username';");
	echo " <script> window.location='registerSetting.php'; </script>";
}

elseif($x==3)
{
	$conn->query("insert ignore into boxusers select * from register_users;");
	$conn->query("delete from register_users;");
	echo " <script> window.location='registerSetting.php'; </script>";
}

echo "<script> alert('checking');  </script>";

?>









<!DOCTYPE html>
<html>
<head> </head>
<body> welcome </body>
</html>
