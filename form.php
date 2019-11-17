<?php 
include_once("db.php");
$query = "SELECT * FROM comments";
$data = mysqli_query($dbc,$query);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link href=".css/style.css" rel="stylesheet">
</head> 
<body>
<form  action="comment.php" method="post">
  <p> 
    <label>Комментарий:</label>
    <br />
    <textarea name="comment" cols="70" rows="10"></textarea>
  </p>
  <p>
    <input type="submit" name="submit" value="Отправить" />
  </p>
</form>
<div class="comments_wrap">
   <ul>
       <li>
            <div class="comment">
               <div class="author">
                    Имя
                  <span class="date">дата</span>
               </div>
                
              <div class="comment_text">комментарий 1</div>
           </div>
           <ul>              
               <li>                    
                   <div class="comment">                        
                      <div class="author">                           
                          Имя                            
                          <span class="date">дата</span>                        
                       </div>                                                
                       <div class="comment_text">комментарий 2</div>                    
                   </div>                 
                </li>             
            </ul>                        
        </li>     
    </ul> 
</div> 
<div class="comment1">
<?php 
    if($data && mysqli_num_rows($data) > 0) {
      while ($row = mysqli_fetch_row($data)) {
        echo "<tr>";
        echo "<td>";
        echo (json_encode($row));
        $user_id = $row[3];
        $query = "SELECT name FROM users WHERE user_id = '$user_id'";
        $users = mysqli_query($dbc,$query);
        $user = mysqli_fetch_row($users);
        echo ("\tuser:".json_encode($user[0]));
        echo "</td><br>";
        echo "</tr>";
      }
    }
?>
</div>
</body>

</html>

