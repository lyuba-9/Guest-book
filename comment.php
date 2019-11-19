<?php
include_once("db.php");
if(isset($_POST['submit'])) {
	//echo(json_encode($_POST));
	$comment = mysqli_real_escape_string($dbc, trim($_POST['comment']));
	$parent_id = mysqli_real_escape_string($dbc, trim($_POST['parent_id']));
	if(!empty($comment)) {                                       
		//$query = "SELECT * FROM `comments` WHERE name = '$name'";
		$user_id = $_COOKIE['user_id'];
        $dateObj = new DateTime();
        $date = $dateObj->format('d/m/Y H:i:s');
		//echo (json_encode($date));
		if(empty($parent_id)) {
		$query ="INSERT INTO `comments` (date, comment, user_id ) VALUES ('$date','$comment', '$user_id')";
		}
		else{
		$query ="INSERT INTO `comments` (date, comment, user_id, parent_id ) VALUES ('$date','$comment', '$user_id', '$parent_id')";
		}
		if (mysqli_query($dbc,$query)) {
			header('Location: form1.php'); exit();	
		}
		else {
			echo 'Произошла ошибка: '. mysqli_error($dbc);
		}
		mysqli_close($dbc);
		exit();
	}
}

