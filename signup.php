<?php
include_once("db.php");
if(isset($_POST['submit'])){
	$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
	$password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
	$password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
	$firstname = mysqli_real_escape_string($dbc, trim($_POST['name']));
	$country = mysqli_real_escape_string($dbc, trim($_POST['country']));
	$age = mysqli_real_escape_string($dbc, trim($_POST['age']));
	if(!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)&& !empty($firstname)&& !empty($country)&& !empty($age)) {                                       
		$query = "SELECT * FROM `users` WHERE username = '$username'";
		$data = mysqli_query($dbc, $query);
		if(mysqli_num_rows($data) == 0) {
			$query ="INSERT INTO `users` (username, password, name, country, age) VALUES ('$username', SHA('$password2'),'$firstname','$country','$age')";
			mysqli_query($dbc,$query);
			echo 'Всё готово, можете авторизоваться';
			mysqli_close($dbc);
			exit();
		}
		else {
			echo 'Логин уже существует';
		}

	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link href="css/style.css" rel="stylesheet">
</head>
<body>
<content>
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<label for="username">Введите ваш логин:</label>
	<input type="text" name="username">
	<label for="password">Введите ваш пароль:</label>
	<input type="password" name="password1">
	<label for="password">Введите пароль еще раз:</label>
	<input type="password" name="password2">
	<label for="name">Введите вашe имя:</label>
	<input type="text" name="name">
	<label for="country">Введите ваш город:</label>
	<input type="text" name="country">
	<label for="age">Введите ваш возраст:</label>
	<input type="text" name="age">
	<button type="submit" name="submit">Вход</button>
	</form>
</content>
</body>

</html>