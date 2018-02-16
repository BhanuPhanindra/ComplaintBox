<?php
include 'config.php';

$postID = $_GET['id'];


if($_SESSION['username'] == 'admin')
{
	$sql = "DELETE FROM posts WHERE postID=? ;";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i",$postID);

	if($stmt->execute())
	echo "<script>alert('Post Deleted'); window.location = 'index.php';</script>";
}

else
{
	$sql = "DELETE FROM posts WHERE postID=? AND username='".$_SESSION['username']."';";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i",$postID);

	if($stmt->execute())
	echo "<script>alert('Post Deleted'); window.location = 'index.php';</script>";
}
?>