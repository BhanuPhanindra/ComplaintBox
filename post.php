<?php

include 'config.php';

$postID = $_GET['postID'];
echo "<script>var post_id=".$postID."</script>";

$post = $conn->query("SELECT * FROM posts WHERE postID=".$postID);
if($post->num_rows == 0) 
{
	echo "Post NOT Found!";
	exit();
}

$result = $conn->query("select * from comments where postID=".$postID.";");
$record_query = $conn->query("select * from posts where postID=".$postID.";");
$record = $record_query->fetch_assoc();


$view = $conn->query("SELECT * FROM likes WHERE postID=".$postID." AND username='".$_SESSION['username']."';");
if($view->num_rows == 0)
{
	$conn->query("INSERT INTO likes VALUES (".$postID." , '".$_SESSION['username']."' ,'0' , '1');");
	$conn->query("UPDATE posts SET views=views+1 WHERE postID=".$postID);
}


function getcomment()
{
	global $result;
	while($var=$result->fetch_assoc())
	{
		echo "<li id='commentlist'> @".$var['username']."<br> ".$var['comment']."<hr> </li>";
	}
	
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
  //Query to insert comment
  $sql = "INSERT INTO comments(username,postID,comment) VALUES ('".$_SESSION['username']."',".$postID.",'".$_POST['comment']."');";
  
  //Sending comment to the database
  if($conn->query($sql) === TRUE)
  {
    echo "<script>alert('Comment  Submitted'); window.location='post.php?postID=".$postID."'</script>";
  }
  else 
  {
    echo $sql;
    echo $conn->error;  

	echo "<script>alert('Chicka bumbum 123')</script>";
  }
}

$statu = $conn->query("select like_status from likes where postID=".$postID.";");
$status = $statu->fetch_assoc();

function getPostTitle()
{
	global $record;
	echo $record['title'];
}

function getPostContent()
{
	global $record;
	echo $record['content'];
}

function getlike()
{
  global $record;
  
  echo $record['likes'];
  change_color();
 
}

function getdislike()
{
  global $record;
  
  echo $record['dislikes'];
  change_color();

  
}

function getview()
{
  global $record;
  
  echo $record['views'];
}



$statu = $conn->query("select * from likes where postID=".$postID." and username='".$_SESSION['username']."'");
$status = $statu->fetch_assoc();


function change_color()
{
  global $status;
  
  if($status['like_status']=='1')
  {   
    echo "<script>
	$(document).ready(function(){
    $('#like_color').addClass('clicked');
    $('#dislike_color').removeClass('clicked');});
    </script>";
  }

  elseif($status['like_status']=='-1')
  {
    echo "<script>
    $(document).ready(function(){
    $('#like_color').removeClass('clicked');
    $('#dislike_color').addClass('clicked');});
    </script>";
  }
  else
  {
    echo "<script>
    $(document).ready(function(){
    $('#like_color').removeClass('clicked');
    $('#dislike_color').removeClass('clicked');});
    </script>";
  }
}



?>


<DOCTYPE html>
<html>
<head>

<title> POST PAGE</title>
<link rel='stylesheet' type='text/css' href='post.css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
</head>

<body>
<!-- Header -->
<div id="topbar">
<h1 class='inline' ><a href='index.php' class='goHomeLink'> THE COMPLAINT BOX </a></h1>
<form action = 'search.php' method = 'POST' class='inline'>
<input type='text' placeholder='Search for Posts' name='keyword' class='searchText' required></input>
</form>
</div>

<!--<div id="result"> </div> -->

<div id="postarea">
<h2 id="posttitle"> <?php getPostTitle() ?> </h2>
<div id="postcontent"><p><?php getPostContent() ?></p> </div>
<ul>

<li> <button title='like' class='button like fade' onclick="addlike(1)" ><?php getlike()?>
<div id="txtHint1"> </div>      <i class='material-icons' id="like_color">thumb_up</i> </button> </li>

<li> <button class="button dislike fade" onclick="addlike(-1)" ><?php getdislike() ?> 
<div id="txtHint2"> </div>      <i class="material-icons" id="dislike_color" >thumb_down</i> </button> </li>

<li> <button class="button comment fade" id='btn'>Comment <br><i class="material-icons" style="font-size:18px">comment</i>  </button> </li>

<li> <button class="button view fade"> <?php getview() ?> <br> <i class="material-icons" style="font-size:18px">visibility</i> </button> </li>

</ul>

<?php 

	if($_SESSION['username'] == 'admin' OR $_SESSION['username'] == $record['username'])

	echo "<a href='delete.php?id=".$record['postID']."' class='delete'>Delete this Post</a>";
?>


</div>

<div id ="commentarea">
<h3 style="color:white;"> COMMENTS </h3>

<ul id = "commentlist">
<pre>
<?php getcomment() ?> 
</pre>
</ul>
<!--<input type='submit' id='btnsend' value='send'> -->
</div>

<?php

echo "
<div id='modal'>
<div id='modalcontent'>
<form name='commentform' action='post.php?postID=".$postID."' id='form2' method='post'>
<textarea id='commentbox' name='comment' rows='10' cols='70' placeholder='Enter your comments here' required>
</textarea> <br>

<button type='submit' id='btnsend1'> <span> Send </span> </button>
</form>
</div>
</div>";

?>




<!-- Javascript -->
<script>

// Get the modal
var modal = document.getElementById('modal');

// Get the button that opens the modal
var btn = document.getElementById("btn");

// When the user clicks the button, open the modal 
btn.onclick = function() 
{
    modal.style.display = "block";
}


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) 
{
    if (event.target == modal) 
	{
        modal.style.display = "none";
    }
}

function addlike(x)
{
	window.location = 'getlike.php?q=' + x + '&postID=' + post_id;

}
</script>


</body>
</html>