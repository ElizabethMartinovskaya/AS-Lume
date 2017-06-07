<?php
require_once 'sql_functions.php';
require_once 'functions_get_id.php';
$name = basename($_FILES['Image_src']['name']);
$tmp_name = $_FILES['Image_src']['tmp_name'];
$type_file = str_replace('image/','.',$_FILES['Image_src']['type']);
$valid_types = array('image/jpeg','image/jpg');
$image_info = getimagesize($tmp_name);
$max_size = 10485760;
Add_photo($image_info,$tmp_name,$type_file,$valid_types,$max_size,'Lume System','Company',$_POST['description_image'],$image_info[0],$image_info[1],$_POST['Source'],$_POST['type_data'],$_POST['image_N'],$_POST['image_E']);
function Add_photo($image_info,$tmp_name_file,$type_file,$valid_types,$max_size,$users_name,$type_user,$description_image,$width_image,$height_image,$Source,$type_data,$image_N,$image_E){
    $now_date = date('Y-m-d H:i:s');
    $id_author = Get_Id_Author($users_name,$type_user);
    $id_properties = Get_Id_Properties_Image($width_image,$height_image);
    $id_event = Get_Id_Event($Source,$type_data);
    $add_update = sql_update("INSERT INTO `image` (`Image_src`,`id_author`,`description_image`,`id_properties`,`id_event`,`publication_date`,`id_category_image`,`image_N`, `image_E`, `isConfirmed`) 
                              VALUES ('{$tmp_name_file}','{$id_author}','{$description_image}','{$id_properties}','{$id_event}','{$now_date}',NULL,'{$image_N}','{$image_E}', '1')");
    $id_upload_file = sql_select("SELECT `id_image` FROM `image` WHERE `image`.`Image_src`='{$tmp_name_file}'");
    $id_upload_file = mysqli_fetch_assoc($id_upload_file)['id_image'];
    $Image_src = "images/".$id_upload_file.$type_file;
    $update_src = sql_update("UPDATE `image` SET `Image_src` = '{$Image_src}' 
                              WHERE `Image_src` = '{$tmp_name_file}'");
    if (!$add_update || !$update_src){
        echo('Ошибка! Фотография не добавлена');
    }
    else{
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!in_array($image_info['mime'], $valid_types)) {
                die('Запрещённый тип файла. Необходимо загрузить файл типа .jpg (.jpeg)');
            }
            if($_FILES["Image_src"]["size"] > $max_size) {
                die("Размер файла не должен превышать 10 мегабайт.");
            }
            if (move_uploaded_file($tmp_name_file, '../'.$Image_src)){
                $url = 'http://35.184.65.83/cgi-bin/uploadpost.py';
                $data = array(
                    'image_id' => $id_upload_file,
                    'file' => $_FILES['Image_src'],
                    'key' => 'lume'
                );
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data)
                    )
                );
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                if ($result === FALSE) {
                }
                echo('Фотография добавлена');
            }
            else {
                $delete_upload_file = sql_update("DELETE FROM `image` WHERE `id_image`='$id_upload_file'");
                if (!$delete_upload_file){
                    echo "Ошибка! Фотография не добавлена";
                }
                echo "Ошибка! Фотография не добавлена";
            }
        }
    };
}

