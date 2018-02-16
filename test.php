<?php

//Connecting to database and also checking for users session 
include 'config.php';


//Variable to keep track of next button
$showNext = TRUE;


//Storing posts from database
$result;


//Fucntion to get a post
function getPost()
{
	global $result, $showNext;
	if($record = $result->fetch_assoc())
	{
		$showNext = TRUE;
		
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
		
		return $string;
	}
	
	else 
	{
		$showNext = FALSE;
		return '';
	}
}



//Function to get next 8 posts and diplay them
function get8Posts()
{
	global $start_of_next, $result, $conn, $showNext;
	
	//Appropriate query based on top, recent and your posts
	if($_POST['sortOrder'] == 'recent')
	{
		$result = $conn->query("SELECT * FROM posts ORDER BY date_time DESC LIMIT 8 OFFSET ".$_POST['skip']);
	}
	else if($_POST['sortOrder'] == 'top')
	{
		$result = $conn->query("SELECT * FROM posts ORDER BY likes DESC, views DESC LIMIT 8 OFFSET ".$_POST['skip']);
	}
	else if($_POST['sortOrder'] == 'your')
	{
		$result = $conn->query("SELECT * FROM posts WHERE username ='".$_SESSION['username']."' ORDER BY postID DESC LIMIT 8 OFFSET ".$_POST['skip']);
	}

	//Html to be inserted into unordered list
	echo "<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>";
	
	
	//Next button removal and addition
	if($showNext)
	{
		echo "<script>document.getElementById('nextButton').style.visibility = 'visible';</script>";
	}
	
	else
	{
		echo "<script>document.getElementById('nextButton').style.visibility = 'hidden';</script>";
	}
}

//Calling the get8Post function
get8Posts();
?>
