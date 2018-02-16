<?php

//Connecting to database and also checking for users session
include 'config.php';

//Getting keyword through POST
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$sql = "SELECT * FROM posts WHERE title LIKE '%".$_POST['keyword']."%';";
	
	$result = $conn->query($sql);		
}

function getSearch()
{
	global $result;
	if($result)			//If matches found
	{
		echo "<h2 class='PostHeading'>Search Results:</h2><hr>";
		while($record = $result->fetch_assoc())
		{
			$string = "<h2 class='PostHeading'><a class='postLink' href='post.php?postID="		//Adding postID to link
			.$record['postID'].
			"'>"
			.$record['title'].
			"</a></h2><span class ='userspan'>@"
			.$record['username'].
			"</span><span class ='date'>"
			.date('d/m/Y',strtotime($record['date_time'])).			//d/m/Y of date_time
			"&emsp;&emsp;"
			.date('h:i A',strtotime($record['date_time'])).			//Time of date_time
			"</span><br><br><span class='views like'>"
			.$record['likes'].
			"</span><i class='material-icons like'>thumb_up</i><span class='views dislike'>"
			.$record['dislikes'].
			"</span><i class='material-icons dislike'>thumb_down</i><span title='views' class='views'><i class='material-icons'>visibility</i>"
			.$record['views'].
			"</span><br><hr>";
			
			echo "<li>".$string."</li>";
		}
	}
	else echo "<span class='resultSearch'>No Search Results<span>";    //If no results
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="searchstyle.css">   <!-- Stylesheet -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">  <!-- Google icons -->
</head>

<body>


<!-- Header -->
<div id="topbar">
<h1 class='inline'> <a href='index.php' class='goHomeLink'>THE COMPLAINT BOX </a></h1>   <!-- Go to home link-->
<form action = 'search.php' method = 'POST' class='inline'>
<input type='text' placeholder='Search for Posts' name='keyword' class='searchText' required></input>
</form>
</div>

<!-- Get search results -->
<ul class="postlist">
<?php getSearch(); ?>
<ul>



</html>