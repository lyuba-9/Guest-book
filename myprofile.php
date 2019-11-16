<?php 
$dbc = mysqli_connect('localhost', 'root', '1234', 'guest');
$user_id = $_COOKIE['user_id'];
$query = "SELECT `name` , `username`, `country`, `age`, `img_file` FROM `users` WHERE user_id = '$user_id'";
$data = mysqli_query($dbc,$query);

if($data && mysqli_num_rows($data) == 1) {
  $row = mysqli_fetch_assoc($data);
}
if (isset ($_POST['submit'])){
if(isset($_POST['name']) && isset($_POST['country']) && isset($_POST['age'])){
    $name= $_POST['name'];
    $country= $_POST['country'];
    $age = $_POST['age'];
    //______________
    $query = "UPDATE users SET name='$name', country='$country', age='$age' WHERE user_id='$user_id'";
    $result = mysqli_query($dbc, $query) or die("Ошибка " . mysqli_error($dbc)); 
      if($result)
        echo "Данные обновлены";
}}

if(isset($_POST['upload'])) {
    $fupload = $_FILES['fupload'];
    // echo json_encode($fupload);
if(!isset($fupload) or empty($fupload) or $fupload =='') {
            //если переменной не существует (пользователь не отправил    изображение),то присваиваем ему заранее приготовленную картинку с надписью    "нет аватара"
            $avatar    = "avatars/net-avatara.jpg"; //можете    нарисовать net-avatara.jpg или взять в исходниках
    }          
else {

            //иначе - загружаем изображение пользователя
            $path_to_90_directory    = 'avatars/';//папка,    куда будет загружаться начальная картинка и ее сжатая копия          
         
if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['fupload']['name']))//проверка формата исходного изображения
                      {                 
                               $filename =    $_FILES['fupload']['name'];
                               $source =    $_FILES['fupload']['tmp_name']; 
                               $target =    $path_to_90_directory . $filename;
                               move_uploaded_file($source,    $target);//загрузка оригинала в папку $path_to_90_directory           
if(preg_match('/[.](GIF)|(gif)$/',    $filename)) {
                     $im    = imagecreatefromgif($path_to_90_directory.$filename) ; //если оригинал был в формате gif, то создаем    изображение в этом же формате. Необходимо для последующего сжатия
                     }
if(preg_match('/[.](PNG)|(png)$/',    $filename)) {
                     $im =    imagecreatefrompng($path_to_90_directory.$filename) ;//если    оригинал был в формате png, то создаем изображение в этом же формате.    Необходимо для последующего сжатия
                     }
                     
if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/',    $filename)) {
                               $im =    imagecreatefromjpeg($path_to_90_directory.$filename); //если оригинал был в формате jpg, то создаем изображение в этом же    формате. Необходимо для последующего сжатия
                     }           
//СОЗДАНИЕ КВАДРАТНОГО ИЗОБРАЖЕНИЯ И ЕГО ПОСЛЕДУЮЩЕЕ СЖАТИЕ    ВЗЯТО С САЙТА www.codenet.ru           
// Создание квадрата 90x90
            // dest - результирующее изображение 
            // w - ширина изображения 
            // ratio - коэффициент пропорциональности           
$w    = 90;  //    квадратная 90x90. Можно поставить и другой размер.          
// создаём исходное изображение на основе 
            // исходного файла и определяем его размеры 
            $w_src    = imagesx($im); //вычисляем ширину
            $h_src    = imagesy($im); //вычисляем высоту изображения
                     // создаём    пустую квадратную картинку 
                     // важно именно    truecolor!, иначе будем иметь 8-битный результат 
                     $dest = imagecreatetruecolor($w,$w);           
         //    вырезаем квадратную серединку по x, если фото горизонтальное 
if    ($w_src>$h_src) 
                     imagecopyresampled($dest, $im, 0, 0,
                                         round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                                      0, $w, $w,    min($w_src,$h_src), min($w_src,$h_src));           
         // вырезаем    квадратную верхушку по y, 
                     // если фото    вертикальное (хотя можно тоже серединку) 
if    ($w_src<$h_src) 
                     imagecopyresampled($dest, $im, 0, 0,    0, 0, $w, $w,
                                      min($w_src,$h_src),    min($w_src,$h_src));           
         // квадратная картинка    масштабируется без вырезок 
if ($w_src==$h_src) 
                     imagecopyresampled($dest,    $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src);           
$date=time();    //вычисляем время в настоящий момент.
            imagejpeg($dest,    $path_to_90_directory.$date.".jpg");//сохраняем    изображение формата jpg в нужную папку, именем будет текущее время. Сделано,    чтобы у аватаров не было одинаковых имен.          
//почему именно jpg? Он занимает очень мало места + уничтожается    анимирование gif изображения, которое отвлекает пользователя. Не очень    приятно читать его комментарий, когда краем глаза замечаешь какое-то    движение.          
$avatar    = $path_to_90_directory.$date.".jpg";//заносим в переменную путь до аватара. 

$delfull    = $path_to_90_directory.$filename; 
            unlink    ($delfull);//удаляем оригинал загруженного    изображения, он нам больше не нужен. Задачей было - получить миниатюру.
            }
            else 
                     {
                                //в случае    несоответствия формата, выдаем соответствующее сообщение
                     exit ("Аватар должен быть в    формате <strong>JPG,GIF или PNG</strong>");
                             }
            //конец процесса загрузки и присвоения переменной $avatar адреса    загруженной авы
            }
            $query = "UPDATE users SET img_file='$avatar' WHERE user_id='$user_id'";
    $result = mysqli_query($dbc, $query) or die("Ошибка " . mysqli_error($dbc)); 
      if($result)
        echo "Данные обновлены";

echo("Avatar: ". $avatar);
    
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Настройки пользователя</title>
</head>
<body>
<div align="center"><h4>Настройки пользователя</h4></div>
<form method='POST'  enctype="multipart/form-data">
            <input type='hidden' name='user_id' value='$user_id' />
            <img src="<?php echo($row['img_file']) ?>">
            <p>Изменить аватар :
            <input type="file" name="fupload" size='2048'></p>
            <input type="submit" name="upload" value="Загрузить">
            <p>Изменить имя:<br> 
            <input name='name' type='text' value="<?php echo($row['name']) ?>"></p>
            <p>Изменить город: <br> 
            <input name="country" type="text" value="<?php echo($row['country']) ?>"></p>
            <p>Изменить возраст: <br> 
            <input name='age' type='text' value="<?php echo($row['age']) ?>"></p>
            <input type='submit' name='submit' value='Сохранить'>
            </form>
</body>
</html>