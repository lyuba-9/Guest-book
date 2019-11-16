<?php
$dbc = mysqli_connect('localhost', 'root', '1234', 'guest');
if(isset($_POST['submit'])) {
	$comment = mysqli_real_escape_string($dbc, trim($_POST['comment']));
	if(!empty($comment)) {                                       
		$query = "SELECT * FROM `comments` WHERE name = '$name'";
		$user_id = $_COOKIE['user_id'];
        $dateObj = new DateTime();
        $date = $dateObj->format('d/m/Y H:i:s');
		echo (json_encode($date));
		$query ="INSERT INTO `comments` (date, comment, user_id ) VALUES ('$date','$comment', '$user_id')";
		mysqli_query($dbc,$query);
		mysqli_close($dbc);
		exit();
	}
}
