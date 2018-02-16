<?php
include 'config.php';

$q = $_GET['q'];
$postID = $_GET['postID'];

//echo "<script>alert('".$q."')</script>";
$string = "SELECT like_status FROM likes WHERE postID=".$postID." AND username='".$_SESSION['username']."';";
$result = $conn->query($string);

if($result->num_rows == 0)
{
	$conn->query("INSERT INTO likes VALUES (".$postID.",'".$_SESSION['username']."','0','0')");
	echo "<script> alert('Bing!')</script>";
}

$result = $conn->query($string);
$status = $result->fetch_assoc();
 

if($status['like_status']=='1' && $q==1)
{
  $conn->query("update likes 
   set like_status = '0'
   where postID = ".$postID." AND username='".$_SESSION['username']."';");
  //code to decrement like
  declike();
  goBack();


}

elseif($status['like_status']=='0' && $q==1)
{
  $conn->query("update likes 
    set like_status = '1'
    where postID = ".$postID." AND username='".$_SESSION['username']."';");

  //code to increment like
  inclike();
  goBack();
  
}

elseif($status['like_status']=='-1' && $q==1)
{
  $conn->query("update likes 
        set like_status = '1'
        where postID = ".$postID." AND username='".$_SESSION['username']."';");

  //code to increment like AND decremnt dislike
  inclike();
  decdislike();
  goBack();
}



elseif($status['like_status']=='-1' && $q==-1)
{
  $conn->query("update likes 
        set like_status = '0'
        where postID = ".$postID." AND username='".$_SESSION['username']."';");

  //code to decrement dislike
  decdislike();
  goBack();
}

elseif($status['like_status']=='0' && $q==-1)
{
$conn->query("update likes 
set like_status = '-1'
where postID = ".$postID." AND username='".$_SESSION['username']."';");

  //code to increment dislike
  incdislike();
  goBack();
}

elseif($status['like_status']=='1' && $q==-1)
{
  $conn->query("update likes 
        set like_status = '-1'
        where postID = ".$postID." AND username='".$_SESSION['username']."';");

  //code to increment dislike AND decremnt like
  incdislike();
  declike();
  goBack();
}



function inclike()
{
	global $conn, $postID;
    $conn->query("update posts 
    set likes = likes +1
    where postID = ".$postID.";");
}



function declike()
{
	global $conn, $postID;
    $conn->query("update posts 
    set likes = likes -1
    where postID = ".$postID.";");
}



function incdislike()
{
	global $conn, $postID;
    $conn->query("update posts 
    set dislikes = dislikes +1
    where postID = ".$postID.";");
}



function decdislike()
{
	global $conn, $postID;
    $conn->query("update posts 
    set dislikes = dislikes -1
    where postID = ".$postID.";");
}

function goBack()
{
	global $postID;
	echo "<script>window.location = 'post.php?postID=".$postID."'</script>";
}
 
?>



