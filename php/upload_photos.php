<?php
$upload_dir = '../images';
$upload_file = $upload_dir.$_FILES["Image_src"]["name"];
$types = array('image/gif', 'image/png', 'image/jpeg', 'image/jpg');
$size = 10485760;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_info = getimagesize($_FILES['Image_src']['tmp_name']);
    if (!in_array($image_info['mime'], $types)) {
        die('Запрещённый тип файла. Необходимо загрузить файл типа .jpeg, .jpg, .png или .gif');
    }
    if($_FILES["Image_src"]["size"] > $size) {
        die("Размер файла не должен превышать 10 мегабайт.");
    }

    if(is_uploaded_file($_FILES["Image_src"]["tmp_name"])) {
        move_uploaded_file($_FILES["Image_src"]["name"], $upload_file);

    }
    else {
        echo("Ошибка загрузки изображения");
    }
}
function Add_photo($users_name,$type_user,$width_image,$height_image,$Image_src,$description_image,$Source,$type_data,$image_N,$image_E){
    if (mysqli_fetch_assoc(Get_Validation($Image_src))) {
        echo "Такое имя фотографии уже занято";
        return;
    }
    $now_date = date('Y-m-d H:i:s');
    $id_author = Get_Id_Author($users_name,$type_user);
    $id_properties = Get_Id_Properties_Image($width_image,$height_image);
    $id_event = Get_Id_Event();
    $add_update = sql_update("INSERT INTO `image` (`id_author`, `id_properties`, `Image_src`, `publication_date`, `id_event`,  `description_image`,`Source`,`image_N`, `image_E`, `isConfirmed`) 
                              VALUES ('{$id_author}', '{$id_properties}','{$Image_src}', '{$now_date}', '{$id_event}',  '{$description_image}','{$Source}','{$type_data}','{$image_N}', '{$image_E}')");
    if (!$add_update){
        echo('Ошибка! Пользователь не добавлен');
    }
    else{
        echo('Пользователь добавлен');
    }
    ;
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
    if ($id_properties_image = mysqli_fetch_assoc($id_properties_image))
    {
        sql_update("INSERT INTO `properties_images` (`width_image`), (`height_image`) 
                    VALUES ('{$width_image}', '{$height_image}')");
        $result_id_properties_image = mysqli_fetch_assoc(sql_select("SELECT `id_properties_image` FROM `properties_images`
                               WHERE `width_image`='{$width_image}' 
                               AND `height_image` = '{$height_image}'"));
    }
    return $result_id_properties_image['id_properties_image'];
}
function GetValidation($Image_src){

}