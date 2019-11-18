<?php 
include_once("db.php");
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
        if(isset ( $row[4])&& $row[4]!=0) {
          continue;
        }
        //echo (json_encode($row));
        $user_id = $row[3];
        $query = "SELECT name FROM users WHERE user_id = '$user_id'";
        $users = mysqli_query($dbc,$query);
        $user = mysqli_fetch_row($users);
        $com_id=$row[0];

		echo ('
      
			<ul>              
               <li>
					<div class="comment">
					   <div class="author">
							'. $user[0] .'
						  <span class="date">'.$row[1].'</span>
					   </div>
					  <div class="comment_text">'.$row[2].'</div> 
				   </div>
          ');
        $query = "SELECT * FROM `comments` WHERE id = '$com_id'";
        $com = mysqli_query($dbc,$query);
        if($com && mysqli_num_rows($com) > 0) {
          while ($row_1 = mysqli_fetch_row($com)){
            echo
             ('<div class="comment">
               <div class="author">
            
                <span class="date">'.$row_1[1].'</span>
               </div>
              <div class="comment_text">'.$row_1[2].'</div> 
             </div>');
          }
        }
        echo('
            <form method="post" action="comment.php">
           <div class="Otvet">
           <label>NEW1</label>
                <input type="text" name="comment" size="40">
              <p>
              <input type="text" size="40" class="otvet2" name="parent_id" style= "display: none;" value="'.$row_1[0].'">
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

