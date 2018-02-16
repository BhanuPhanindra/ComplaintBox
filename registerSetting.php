
<?php
include 'config.php';
if($_SESSION['username'] !== 'admin')
{
	echo "<script> alert('Acess Denied'); </script>";
	echo "<script> window.location='login.php'; </script>";
}

$sql = $conn->query("select * from register_users;");

function getcontent()
{
	global $sql;
	while($record = $sql->fetch_assoc())
    {
    	//echo"<script> var username = '".$record['username']."'; </script> ";
	     echo "<li>";
	     echo " Username: ".$record['username']."<br> ";
	     echo " <button class=\"add button\" onclick = \"add('".$record['username']."')\" > ADD </button> ";
	     echo " <button class=\"deny button\"  onclick = \"deny('".$record['username']."')\" > DENY </button> ";
	     echo "<hr>  </li>";
   } 

}



?>



<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="registerSetting.css">
</head>

<body>
<div id="topbar">
<a href="index.php" class="goHomeLink"><h1 id ='complaintHead'> THE COMPLAINT BOX </h1></a>
</div>

<div id = "area">
<ul id = 'list'>
<?php
getcontent();
?>
<button onclick="all1()"> ADD all</button>
</ul>
</div>

<script>

function add(user)
{
	window.location = 'process.php?x=1&username='+user;
}

function deny(user)
{
	window.location = 'process.php?x=2&username='+user;
}

function all1()
{
	window.location = 'process.php?x=3';
}
</script>

</body>
</html>