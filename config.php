<?php 
//This file includes DB connection setup and session variable check


//Login check
session_start();

if(!isset($_SESSION['logged']) || empty($_SESSION['logged']))
{
	echo "<script> alert('You have to be logged in yo');</script>;";
	echo "<script>window.location='login.php';</script>";
}

//Connecting to database
$conn = new mysqli("localhost","root","","mydb");
if($conn->connect_error)
{
	echo "<script> alert('connection error'); </script>";	
}

?>
