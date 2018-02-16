<?php

//Connecting to database and also checking for users session 
include 'config.php';

//Storing posts from database
$result = $conn->query("SELECT * FROM posts ORDER BY date_time DESC LIMIT 8; ");



//Fucntion to get a post
function getPost()
{
	global $result;
	if($record = $result->fetch_assoc())
	{
		$string = "<h2 class='PostHeading'><a class='postLink' href='post.php?postID="   //Adding postID to link
		.$record['postID'].
		"'>"
		.$record['title'].
		"</a></h2><span class ='userspan'>@"
		.$record['username'].
		"</span><span class ='date'>"
		.date('d/m/Y',strtotime($record['date_time'])).    //d/m/Y of date_time
		"&emsp;&emsp;"
		.date('h:i A',strtotime($record['date_time'])).    //Time of date_time
		"</span><br><br><span class='views like'>"
		.$record['likes'].
		"</span><i class='material-icons like'>thumb_up</i><span class='views dislike'>"
		.$record['dislikes'].
		"</span><i class='material-icons dislike'>thumb_down</i><span title='views' class='views'><i class='material-icons'>visibility</i>"
		.$record['views'].
		"</span><br><hr>";
		
		return $string;
	}
	
	else return '';
}

//Function to get next 8 posts and diplay them
function get8Posts()
{
	global $start_of_next, $result, $conn;
	

	//Html to be inserted into unordered list
	echo "<li>".getPost()."</li>       
	<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>
	<li>".getPost()."</li>";
}


//POST submission
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	//Query to insert 'title' and 'content' of post
	$sql = "INSERT INTO posts (username,views,likes,dislikes,title,content, date_time) VALUES (?, ?, ?, ?, ?, ?, now())";
	
	//Prepare and bind
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('siiiss',$username,$views,$likes,$dislikes,$title,$content);
	
	//Setting username, title, complaint etc.
	if(isset($_POST['anonymous']) AND $_POST['anonymous'] == 'checked')$username = "anonymous";
	else $username = $_SESSION['username'];
	$views = 0;
	$likes=0;
	$dislikes=0;
	$title=$_POST['title'];
	$content=$_POST['complaint'];
	
	//execute
	$stmt->execute();

}

?>

<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" type="text/css" href="style.css">   <!-- Stylesheet -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">   <!-- Google icons -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> <!-- Ajax -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">  <!-- cloudfare icons -->

</head>
<body>



<!-- POST box Modal -->
<div id='mymodal'>
<!-- Form of POST -->
<form class="modal" action="index.php" method="POST" name="postform">

<!-- Title of form -->
<span class="postboxHead">Title</span><br>
<!-- Title Input -->
<input type="text" class="postboxTitle" name="title" required><br><br>
<!-- Heading on Content -->
<span class="postboxHead">Complaint</span><br>
<!-- Content Input -->
<textarea class="postboxText" name="complaint" required></textarea><br>
<!-- Anonymous checkbox -->
<input type="checkbox" name="anonymous" value="checked">Go Anonymous 
<!-- POST button -->
<button type="submit" id="actualPost" >POST</button>

</form>
</div>



<!-- Header -->
<div id="topbar">
<h1 class='inline'> THE COMPLAINT BOX </h1>
<form action = 'search.php' method = 'POST' class='inline'>
<input type='text' placeholder='Search for Posts' name='keyword' class='searchText' required>
</form>
</div>



<!-- Navigation Bar -->
<ul>
  <li><a id='home' class="active" onclick="gotoTopPosts('#home','recent')">Recent Posts</a></li>
  <li><a class='a_class' id="topposts" onclick="gotoTopPosts('#topposts','top')">Top Posts</a></li>
  <li><a class='a_class' id="yourposts" onclick="gotoTopPosts('#yourposts','your')">Your Posts</a></li>
  <li><a class='a_class' id="about" >About</a></li>
  <li><button id="post" onclick="displayPostBox()">POST</button></li>
  <li class='chPass'><a class='changePassword' href="setting.php">Change Password</a>
  <?php
	if($_SESSION['username'] == 'admin')
	echo "<br><a class='changePassword' href='registerSetting.php'>New Registers</a>"
  ?></li>
  <li><button id="logout" onclick="logout()"><i class = 'fa fa-sign-out'></i>Logout</button></li>
</ul>



<!-- Posts List -->
<ul class="postlist">
	<?php get8Posts(); ?>
</ul>



<!-- Prev Button -->
<button id="prevButton" onclick="getPrev()">Prev</button>

<!-- Next Button -->
<button id="nextButton" onclick="getNext()">Next</button>



<!--User welcome -->
<?php //echo "<script>alert('Welcome ".$_SESSION['username']."');</script>" ;?> 



<!-- Javascript -->
<script>

//Setting default post order to recent
var postSort = "recent";


//Change active tab in navigation menu
function gotoTopPosts(selection, sortOrder)
{
	$("#home").removeClass("active");        //remove active class if present
	$("#yourposts").removeClass("active");
	$("#about").removeClass("active");
	$("#topposts").removeClass("active");
	$("#home").addClass("a_class");        //remove active class if present
	$("#yourposts").addClass("a_class");
	$("#about").addClass("a_class");
	$("#topposts").addClass("a_class");
	
	$(selection).addClass("active");         //add active class on clicked tab
	$(selection).removeClass("a_class");
	
	postSort = sortOrder;					//getting order to sort the posts based on tab
	
	skip_number=0;							//set skip_number to 0 on selecting a tab
	document.getElementById("prevButton").style.visibility = "hidden"; //Hiding prev Button
	
	$('.postlist').load('test.php',{sortOrder: postSort, skip: skip_number});
}

//Getting modal from it's ID
var modal = document.getElementById("mymodal");


//Removes modal on clicking anywhere outside POST box
window.onclick = function(event) 
{		
    if (event.target == modal) 
	{
        modal.style.display = "none";
    }
}

//Displays POST box on clicking the 'POST' button
function displayPostBox()
{
	modal.style.display = "block";
}


//number of posts to skip to display the next eight
var skip_number = 0;


//Function to display next 8 posts
function getNext() 
{	
	skip_number += 8; 
	
  $(".postlist").load("test.php",{ sortOrder: postSort, skip : skip_number});
  
  if(skip_number > 0) document.getElementById("prevButton").style.visibility = "visible";
  else document.getElementById("prevButton").style.visibility = "hidden";
}


//Function to display previous 8 posts
function getPrev()
{
	skip_number -= 8;
	$(".postlist").load("test.php",{sortOrder: postSort, skip: skip_number});
	if(skip_number === 0) document.getElementById("prevButton").style.visibility = "hidden";
}


//Logout function
function logout()
{
	window.location = "login.php";
}

</script>

</body>
</html>