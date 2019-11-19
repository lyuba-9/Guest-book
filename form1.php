<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbc = mysqli_connect('localhost', 'root', '1234', 'guest') OR DIE('Ошибка подключения к базе данных');
$query = "SELECT * FROM comments";
$data = mysqli_query($dbc,$query);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link href="css/style.css" rel="stylesheet">
</head> 
<body>
<a href="index.php">Главная</a>
<form  action="comment.php" method="post">
  <p> 
    <label>Комментарий:</label>
    <br />
    <textarea name="comment" cols="70" rows="10"></textarea>
  </p>
  <p>
    <input type="submit" class="button" name="submit" value="Отправить" />
  </p>
</form>
<div class="comments_wrap">
<?php 
    if($data && mysqli_num_rows($data) > 0) {

      while ($row = mysqli_fetch_row($data)) {
      // if(isset ($row[4])&& $row[4]!=0) {
       //continue;
      // }
        //echo (json_encode($row));
        $user_id = $row[3];
        $query = "SELECT name FROM users WHERE user_id = '$user_id'";
        $users = mysqli_query($dbc,$query);
        $user = mysqli_fetch_row($users);
        $parent_id=$row[4];

		    echo ('
    					<div class="comment">
    					   <div class="author">
    							'. $user[0] .'
    						  <span class="date">'.$row[1].'</span>
    					   </div>
    					  <div class="comment_text">'.$row[2].'</div> 
    				  </div>
            ');

        if(isset($parent_id)) {
          continue;
        }

        //if (isset($parent_id) && $parent_id != null) {
        $query = "SELECT * FROM `comments` WHERE id = '$parent_id'";
        $com = mysqli_query($dbc,$query);
        if($com && mysqli_num_rows($com) > 0) {
          while ($row_1 = mysqli_fetch_row($com)){
            echo
             ('<div class="com">
               <div class="author1">
                <span class="date">'.$row_1[1].'</span>
               </div>
              <div class="comment_text">'.$row_1[2].'</div> 
             </div>');
          }
        }
        //}
        echo('
            <form method="post" action="comment.php">
           <div class="Otvet">
                <input type="text" name="comment" size="40">
              <p>
              <input type="text" size="40" class="otvet2" name="parent_id" value="'. $row[0].'" style= "display: none;" >
                <input  type="submit" class="button" name="submit" value="Отправить" />
              </p>
              </div>
         </form>
          ');
      }
    }
?>
</div> 
</body>

</html>

