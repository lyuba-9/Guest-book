<?php
include_once("db.php");
$user_id = $_COOKIE['user_id'];
$query = "SELECT `name` , `username`, `country`, `age` FROM `users` WHERE user_id = '$user_id'";
$data = mysqli_query($dbc,$query);
 
////////Изменение аватары
 
if (isset($_FILES['fupload']['name'])){ //отправлялась ли переменная
 
    if (empty($_FILES['fupload']['name']) AND $_FILES['fupload']['name'] == ''){
        
        $noAvatar = "noAvatar.jpg";//изображение если пользователь не загрузил свое
        $result = mysql_query("SELECT avatar FROM users WHERE user_id='$user_id'");//извлекаем текущий аватар
        $avatarka = mysql_fetch_array($result);
        
        if ($avatarka['avatar'] != $noAvatar) {//если аватар был стандартный, то не удаляем его, ведь у на одна картинка на всех.
            unlink ('avatars/'.$avatarka['avatar']);
        }   
        
    }
 
    else{
        //иначе - загружаем изображение пользователя для обновления
        $path_to_90_directory = 'avatars/';//папка, куда будет загружаться начальная картинка и ее сжатая копия
            
        if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['fupload']['name']))//проверка формата исходного изображения
             {  
                    
                $filename = $_FILES['fupload']['name'];
                $source = $_FILES['fupload']['tmp_name'];   
                $target = $path_to_90_directory . $filename;
                move_uploaded_file($source, $target);//загрузка оригинала в папку $path_to_90_directory
 
            if(preg_match('/[.](GIF)|(gif)$/', $filename)) {
            $im = imagecreatefromgif($path_to_90_directory.$filename) ; //если оригинал был в формате gif
            }
            if(preg_match('/[.](PNG)|(png)$/', $filename)) {
            $im = imagecreatefrompng($path_to_90_directory.$filename) ;//если оригинал был в формате png
            }
            
            if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) {
                $im = imagecreatefromjpeg($path_to_90_directory.$filename); //если оригинал был в формате jpg
            }
     
       
        
        
        $w = 120;  // ширина изображения
        
        $quality = 100; //Качество создаваемого изображения max 100
                
        $w_src = imagesx($im); //вычисляем ширину
        $h_src = imagesy($im); //вычисляем высоту изображения
        
        //Создавать квадратное изображение $rezim = 1
        //Создать изображение пропорционально оригиналу $rezim = 2
        
        $rezim = 1;     
        
        switch ($rezim){
                //**************************** 1
            case "1" : 
                
                 // создаём пустую квадратную картинку 
                 // важно именно truecolor!, иначе будем иметь 8-битный результат 
                 $dest = imagecreatetruecolor($w,$w); 
 
                 // вырезаем квадратную серединку по x, если фото горизонтальное 
                 
                if ($w_src > $h_src){ 
                    imagecopyresampled($dest, $im, 0, 0, round((max($w_src,$h_src)-min($w_src,$h_src))/2), 0, $w, $w, min($w_src,$h_src), min($w_src,$h_src));
                }
                 // вырезаем квадратную верхушку по y, 
                if ($w_src < $h_src){
                    imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, min($w_src,$h_src), min($w_src,$h_src)); 
                }
                 // квадратная картинка масштабируется без вырезок 
                
                if ($w_src == $h_src){
                    imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $h_src); 
                }
 
            break;
                    //***************************** 2
            case "2" : 
                $prop = $w_src/$h_src;
                $h = $w/$prop;
                $dest = imagecreatetruecolor($w,$h); 
                imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $h, $w_src, $h_src); 
            break;
            
        }
        
        
        
 
                
        $random = rand(1000000, 9999999);
        imagejpeg($dest, $path_to_90_directory.$random.".jpg", $quality);//сохраняем изображение формата jpg в нужную папку
        
        $avatar = $random.".jpg";//заносим в переменную путь до аватара.
 
        $delfull = $path_to_90_directory.$filename; 
        unlink ($delfull);//удаляем оригинал загруженного изображения, он нам больше не нужен.
 
        $result = mysqli_query("SELECT avatar FROM users WHERE user_id='$user_id'");//извлекаем текущий аватар пользователя
        $avatarka = mysqli_fetch_array($result);
        
        if ($avatarka['avatar'] != $noAvatar) {//если аватар был стандартный, то не удаляем его, ведь у на одна картинка на всех.
            unlink ('avatars/'.$avatarka['avatar']);
        }
        }
        else{
            //в случае несоответствия формата, выдаем соответствующее сообщение
            exit ("Аватар должен быть в формате <strong>JPG,GIF или PNG</strong>");
        }
        
    }
    
 
    $up = mysqli_query("UPDATE users SET avatar='$avatar' WHERE user_id='$user_id'");//обновляем аватар в базе
    if ($up == true) {//если верно, то отправляем на личную страничку
        echo "<meta http-equiv='Refresh' content='0; URL=myprofile.php? user_id=".$user_id."'>";
    }
 
}
 
 
 
?>
