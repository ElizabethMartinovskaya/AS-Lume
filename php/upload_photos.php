<?php
require_once 'sql_functions.php';
/*var_dump($_FILES);
var_dump($_POST);*/
$name = basename($_FILES['Image_src']['name']);
$tmp_name = $_FILES['Image_src']['tmp_name'];
$type_file = str_replace('image/','.',$_FILES['Image_src']['type']);
$upload_dir = '../images/';
$upload_file = $upload_dir.$name;
$valid_types = array('image/jpeg','image/jpg');
$image_info = getimagesize($tmp_name);
$max_size = 10485760;
Add_photo($image_info,$name,$tmp_name,$type_file,$upload_dir,$upload_file,$valid_types,$max_size,'Lume System','company',$_POST['description_image'],$image_info[0],$image_info[1],$_POST['Source'],$_POST['type_data'],$_POST['image_NE']);
function Add_photo($image_info,$name_file,$tmp_name_file,$type_file,$upload_dir,$upload_file,$valid_types,$max_size,$users_name,$type_user,$description_image,$width_image,$height_image,$Source,$type_data,$image_NE){
    $now_date = date('Y-m-d H:i:s');
    $coordinates = explode(' ', $image_NE);
    $id_author = Get_Id_Author($users_name,$type_user);
    $id_properties = Get_Id_Properties_Image($width_image,$height_image);
    $id_event = Get_Id_Event($Source,$type_data);
    $add_update = sql_update("INSERT INTO `image` (`Image_src`,`id_author`,`description_image`,`id_properties`,`id_event`,`publication_date`,`id_category_image`,`image_N`, `image_E`, `isConfirmed`) 
                              VALUES ('{$tmp_name_file}','{$id_author}','{$description_image}','{$id_properties}','{$id_event}','{$now_date}','','{$coordinates[0]}', '{$coordinates[1]}'), '1'");
    $id_upload_file = sql_select("SELECT `id_image` FROM `image` WHERE `image`.`Image_src`='{$tmp_name_file}'");
    $Image_src = $upload_dir.$id_upload_file.$type_file;
    $update_src = sql_update("UPDATE `image` SET `Image_src` = '{$Image_src}', 
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
            if (move_uploaded_file($tmp_name_file, $upload_file)){
                echo('Фотография добавлена');
            }
            else {
                $delete_upload_file = sql_update("DELETE FROM `image` WHERE `id_image`='$id_upload_file'");
                if (!$delete_upload_file){
                    echo "Ошибка загрузки изображения";
                }
                else{
                    echo("Фотография добавлена");
                }
            }
        }
    };
}
function Get_Id_Author($users_name,$type_user){
    $id_author = sql_select("SELECT `id_users` FROM `users`
                            WHERE `users_name`='{$users_name}'
                            AND `type_user`='{$type_user}'");
    return $id_author;
}
function Get_Id_Properties_Image($width_image,$height_image){
    $id_properties_image = sql_select("SELECT `id_properties_image` FROM `properties_images`
                                      WHERE `width_image`='{$width_image}'
                                      AND `height_image`='{$height_image}'");
    if ($id_properties_image=false)
    {
        sql_update("INSERT INTO `properties_images` (`width_image`), (`height_image`) 
                  VALUES ('{$width_image}', '{$height_image}')");
        $id_properties_image = mysqli_fetch_assoc(sql_select("SELECT `id_properties_image` FROM `properties_images`
                                                                    WHERE `width_image`='{$width_image}' 
                                                                    AND `height_image` = '{$height_image}'"));
    }
    return $id_properties_image['id_properties_image'];
}
function Get_Id_Event($Source,$type_data){
    $id_type = sql_select("SELECT `id_Type` FROM `type_of_data`
                          WHERE `type_data`='{$type_data}'");
    if ($id_type = mysqli_fetch_assoc($id_type))
    {
        $result_id_event = sql_select("SELECT `id_event` FROM `event`
                               WHERE `Source`='{$Source}' 
                               AND `Type_id_Tipe` = '{$id_type['id_type']}'");
        if(!($result_id_event= mysqli_fetch_assoc($result_id_event))) {
            sql_update("INSERT INTO `event` (`Source`), (`Type_id_Type`) 
                      VALUES ('{$Source}', '{$id_type}')");
            $result_id_event = mysqli_fetch_assoc(sql_select("SELECT `id_event` FROM `event`
                                                            WHERE `Source`='{$Source}' 
                                                            AND `Type_id_Type` = '{$id_type}'"));
        }
    }
    else
    {
        sql_update("INSERT INTO `type_of_data` (`type_data`) 
                    VALUES ('{$type_data}')");
        $id_type = mysqli_fetch_assoc(sql_select("SELECT `id_Type` FROM `type_of_data`
                                                WHERE `type_data`='{$type_data}'"));
        sql_update("INSERT INTO `event` (`Source`, Type_id_Type) 
                    VALUES ('{$Source}', '{$id_type['id_type']}')");
        $result_id_event = mysqli_fetch_assoc(sql_select("SELECT `id_event` FROM `event`
                               WHERE `Source`='{$Source}' 
                               AND Type_id_Type = '{$id_type['id_type']}'"));
    }

    return $result_id_event['id_event'];
}