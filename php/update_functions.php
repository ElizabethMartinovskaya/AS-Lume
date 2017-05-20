<?php
require_once 'sql_function.php';
require_once 'upload_photos.php';
if (isset($_POST['form'])){
    if ($_POST['form']=='user_setting'){
        Update_admin_info($_POST['user_name'],$_POST['user_login'],$_POST['user_old_password'],$_POST['user_new_password'],$_POST['user_replay_new_password']);
    }
    elseif ($_POST['form']=='add_item_form'){
        if($_POST['current_li']=="Пользователь")
        {
            if ($_POST['models']==-1){
                echo "Выберите модель устройства";
            }
            else{
                if ($_POST['models']==0){
                    Add_user_full($_POST['users_name'],$_POST['users_surname'],$_POST['users_login'],$_POST['users_password'],$_POST['users_email'],$_POST['name_model'],$_POST['name_brand'],$_POST['screen_size'],$_POST['width_model_screen'],$_POST['height_model_screen'],$_POST['phone_number'],$_POST['country_name'],$_POST['city_name']);
                }
                else{
                    Add_user($_POST['users_name'],$_POST['users_surname'],$_POST['users_login'],$_POST['users_password'],$_POST['users_email'],$_POST['models'],$_POST['phone_number'],$_POST['country_name'],$_POST['city_name']);
                }
            }
        }
        elseif($_POST['current_li']=="Компания"){
            Add_company($_POST['users_name'],$_POST['users_login'],$_POST['users_password'],$_POST['users_email'],$_POST['phone_number'],$_POST['country_name'],$_POST['city_name']);
        }
        elseif($_POST['current_li']=="Акция"){
            Add_stock($_POST['stock_name'],$_POST['stock_type'],$_POST['id_author'],$_POST['description_stock'],$_POST['date_begin'],$_POST['date_end'],$_POST['prize_id']);
        }
        elseif($_POST['current_li']=="Фотография"){
            Add_photo($_POST['users_name'],$_POST['type_user'],$_POST['Image_src'],$_POST['width_image'],$_POST['height_image'],$_POST['Image_src'],$_POST['description_image'],$_POST['Source'],$_POST['type_data'],$_POST['image_N'],$_POST['image_E']);
        }
    }
}
elseif (isset($_POST['operation'])){
    if ($_POST['operation']=='delete') {
        Delete_item($_POST['current_li'], $_POST['id']);
    }
    elseif($_POST['operation']=='create_edit_form'){
        Create_Edit_form($_POST['current_li'], $_POST['id'],(array) json_decode($_POST["list"]) );
    }
    elseif ($_POST['operation']=='edit_item'){
        if ($_POST["current_li"]== "Пользователи")
            Edit_user($_POST['item_id']);
        elseif ($_POST["current_li"]=="Компании")
            Edit_company($_POST["item_id"]);
    }
    elseif ($_POST['operation']=='get_models')
    {
        $all_models = sql_select("SELECT `name_model`,`id_device_info` FROM `model`, `device_info` WHERE `model`.`id_model`=`device_info`.`id_model`");
        $i=0;
        while ($current_model = mysqli_fetch_assoc($all_models)) {
            $models[$i++] = $current_model;
        }
        echo json_encode($models);
    }
}
elseif (isset($_POST['search_form'])){
    print_r($_POST);
}
function Update_admin_info($user_name,$user_log,$user_old_pass,$user_new_pass,$user_replay_new_pass){
    $result = mysqli_fetch_assoc(sql_select("SELECT `admin_name`,`admin_password` 
                          FROM `admins` 
                          WHERE `admin_name`='{$user_name}'"));
    if ($result['admin_password']=$user_old_pass && $user_new_pass==$user_replay_new_pass){
        $update = sql_update("UPDATE `admins` SET `admin_login` = '{$user_log}', 
                              `admin_password`='{$user_new_pass}' 
                              WHERE `admin_name` = '{$user_name}'");
        if (!$update){
            echo('Ошибка! Изменения не сохранены');
        }
        else{
            echo('Изменения сохранены');
        }
    }
} 
function Add_user($user_name,$user_surname,$user_log,$user_pass,$user_email,$id_device_info,$user_phone,$user_country,$user_city){
    $id_city = get_id_city($user_city,$user_country);
    if (mysqli_fetch_assoc(get_validation($user_log,$user_email))) {
        echo "Логин и/или email уже заняты";
        return;
    }
    $add_update = sql_update("INSERT INTO `users` (`users_name`, `users_surname`, `users_login`, `users_password`, `users_email`, 
                              `type_user`, `id_device_info`, `id_city`, `phone_number`) 
                              VALUES ('{$user_name}', '{$user_surname}', '{$user_log}', '{$user_pass}', '{$user_email}', 'user', '$id_device_info','$id_city', '{$user_phone}')");
    if (!$add_update){
        echo('Ошибка! Пользователь не добавлен');
    }
    else{
        echo('Пользователь добавлен');
    }
}
function Add_user_full($user_name,$user_surname,$user_log,$user_pass,$user_email,$model,$brand,$screen_size,$width,$height,$user_phone,$user_country,$user_city){
    $id_city = get_id_city($user_city,$user_country);
    $id_device_info = get_id_device_info($model,$brand,$screen_size,$width,$height);
    if (mysqli_fetch_assoc(get_validation($user_log,$user_email))) {
        echo "Логин и/или email уже заняты";
        return;
    }
    $add_update = sql_update("INSERT INTO `users` (`users_name`, `users_surname`, `users_login`, `users_password`, `users_email`, 
                              `type_user`, `id_device_info`, `id_city`, `phone_number`) 
                              VALUES ('{$user_name}', '{$user_surname}', '{$user_log}', '{$user_pass}', '{$user_email}', 'user', '$id_device_info','$id_city', '{$user_phone}')");
    if (!$add_update){
        echo('Ошибка! Пользователь не добавлен');
    }
    else{
        echo('Пользователь добавлен');
    }
}
function Add_company($user_name,$user_log,$user_pass,$user_email,$user_phone,$user_country,$user_city){
    $id_city = get_id_city($user_city,$user_country);

    if (mysqli_fetch_assoc(get_validation($user_log,$user_email))) {
        echo "Логин или(и) email уже заняты";
        return;
    }
    $add_update = sql_update("INSERT INTO `users` (`users_name`, `users_login`, `users_password`, `users_email`, 
                              `type_user`, `id_city`, `phone_number`) 
                              VALUES ('{$user_name}', '{$user_log}', '{$user_pass}', '{$user_email}', 'company', '$id_city', '{$user_phone}')");
    if (!$add_update){
        echo('Ошибка! Компания не добавлена');
    }
    else{
        echo('Компания добавлена');
    }
}
function Add_stock($stock_name,$stock_type,$id_author,$description_stock,$date_begin,$date_end,$prize_id){
    $d_begin = str_replace("T" , " ", $date_begin).":00";
    $d_end = str_replace("T" , " ", $date_end).":00";
    $add_update = sql_update("INSERT INTO `stock` (`stock_name`, `id_stock_type`, `id_author`, `description_stock`, `date_begin`, `date_end`) 
                              VALUES ('{$stock_name}', '{$stock_type}', '{$id_author}', '{$description_stock}', '$d_begin', '$d_end')");
    if ($id_stock = mysqli_fetch_assoc(sql_select("SELECT MAX(id_stock) FROM `stock` 
                                                    where `stock_name` = '{$stock_name}' AND `id_stock_type` ='{$stock_type}' 
                                                    AND  `id_author` = '{$id_author}' AND `description_stock` = '{$description_stock}' 
                                                    AND `date_begin` = '$d_begin' AND  `date_end` = '$d_end'"))) {
        $add_update_prize = sql_update("INSERT INTO `stock_prize`(`Stock_id_stock`, `id_prize`) VALUES ({$id_stock["MAX(id_stock)"]},$prize_id)");
    }
    if (!$add_update || !$add_update_prize){
        echo('Ошибка! Акция не добавлена');
    }
    else{
        echo('Акция добавлена');
    }
}
function Delete_item($current_li, $id){
    if($current_li=="Акции"){
        $delete_item = sql_update("DELETE FROM `stock` WHERE `id_user`='$id'");
        if (!$delete_item){
            echo "Ошибка! Акция не удалена";
        }
        else{
            echo("Акция удалена");
        }
    }
    elseif ($current_li=="Фотографии"){
        $delete_item = sql_update("DELETE FROM `image` WHERE `id_user`='$id'");
        if (!$delete_item){
            echo "Ошибка! Фотография не удалена";
        }
        else{
            echo("Фотография удалена");
        }
    }
    else{
        $delete_item = sql_update("DELETE FROM `users` WHERE `id_user`='$id'");
        if (!$delete_item){
            echo "Ошибка! Пользователь не удален";
        }
        else{
            echo("Пользователь удалён");
        }
    }
}

function Edit_user($id_item){
    $id_city = get_id_city($_POST['city_name'],$_POST['country_name']);
    if ($_POST['models']==-1){
        echo "Выберите модель устройства";
    }
    else{
        if ($_POST['models'] == 0)
            $id_device_info = get_id_device_info($_POST['name_model'],$_POST['name_brand'],$_POST['screen_size'],$_POST['width_model_screen'],$_POST['height_model_screen']);
        else
            $id_device_info = $_POST['models'];
        $update_item = sql_update("UPDATE `users` SET users_name='{$_POST["users_name"]}',
            `users_surname`='{$_POST['users_surname']}',`users_login`='{$_POST['users_login']}',`users_password`='{$_POST['users_password']}',
            `users_email`='{$_POST['users_email']}',`id_device_info`='$id_device_info',`id_city`='$id_city',
            `phone_number`=".(strlen($_POST['phone_number']) != 13 ? 'NULL':"'{$_POST['phone_number']}'")." WHERE id_user = '$id_item'");
        if (!$update_item)
            echo "Информация не обновлена";
        else
            echo "Информация обновлена";
    }
}

function Edit_company($id_item)
{
    $id_city = get_id_city($_POST['city_name'],$_POST['country_name']);
    $update_item = sql_update("UPDATE `users` SET users_name='{$_POST["users_name"]}',
            `users_login`='{$_POST['users_login']}',`users_password`='{$_POST['users_password']}',
            `users_email`='{$_POST['users_email']}',`id_city`='$id_city',
            `phone_number`=".(strlen($_POST['phone_number']) != 13 ? 'NULL':"'{$_POST['phone_number']}'")." WHERE id_user = '$id_item'");

    if (!$update_item)
        echo "Информация не обновлена";
    else
        echo "Информация обновлена";
}
function get_id_city($city_name, $country_name)
{
    $id_country = sql_select("SELECT `id_country` FROM `country`
                              WHERE `country_name`='{$country_name}'");
    if ($id_country = mysqli_fetch_assoc($id_country))
    {
        $id_city = sql_select("SELECT `id_city` FROM `city`
                               WHERE `city_name`='{$city_name}' 
                               AND id_country = '{$id_country['id_country']}'");
        if(!($id_city= mysqli_fetch_assoc($id_city))) {
            sql_update("INSERT INTO `city` (`city_name`, id_country) 
                        VALUES ('{$city_name}', '{$id_country['id_country']}')");
            $id_city = mysqli_fetch_assoc(sql_select("SELECT `id_city` FROM `city`
                                   WHERE `city_name`='{$city_name}' 
                                   AND id_country = '{$id_country['id_country']}'"));
        }

    }
    else
    {
        sql_update("INSERT INTO `country` (`country_name`) 
                    VALUES ('{$country_name}')");
        $id_country = mysqli_fetch_assoc(sql_select("SELECT `id_country` FROM `country`
                                                     WHERE `country_name`='{$country_name}'"));
        sql_update("INSERT INTO `city` (`city_name`, id_country) 
                    VALUES ('{$city_name}', '{$id_country['id_country']}')");
        $id_city = mysqli_fetch_assoc(sql_select("SELECT `id_city` FROM `city`
                               WHERE `city_name`='{$city_name}' 
                               AND id_country = '{$id_country['id_country']}'"));
    }

    return $id_city['id_city'];
}

function get_id_device_info($model, $brand,$screen_size, $width, $height)
{
    $id_brand = sql_select("SELECT id_brand FROM `brand` WHERE brand_name='$brand'");
    if (!$id_brand = mysqli_fetch_assoc($id_brand)) {
        sql_update("INSERT INTO `brand` (`id_brand`, `brand_name`) VALUES (NULL, '$brand')");
        $id_brand = mysqli_fetch_assoc(sql_select("SELECT id_brand FROM `brand` WHERE brand_name='$brand'"));
    }

    sql_update("INSERT INTO `model` (`id_model`, `name_model`, `id_brand`, `id_type_of_device`, `screen_size`, `width_model_screen`, `height_model_screen`) 
                    VALUES (NULL, '$model', '{$id_brand['id_brand']}', '1', '$screen_size', '$width', '$height')");
    $id_model = mysqli_fetch_assoc(sql_select("SELECT MAX(id_model) FROM `model` WHERE name_model = '$model' AND id_brand = '{$id_brand['id_brand']}' AND screen_size = '$screen_size' 
                            AND width_model_screen = '$width' AND height_model_screen = '$height'"));
    sql_update("INSERT INTO `device_info` (`id_device_info`, `id_os`, `id_model`) VALUES (NULL, '1', '{$id_model['MAX(id_model)']}')");
    return mysqli_fetch_assoc(sql_select("SELECT id_device_info FROM `device_info` WHERE `id_os` = 1 AND `id_model` = '{$id_model['MAX(id_model)']}'"))['id_device_info'];

}

function get_validation($user_login, $user_email)
{
    return sql_select("SELECT id_user as 'is_invalid' FROM `users` WHERE users_login = '$user_login' OR users_email='$user_email' ");
}