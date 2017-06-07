<?php
require_once 'sql_functions.php';
require_once 'functions_get_id.php';
if (isset($_POST["form"])){
    if ($_POST["form"]=="user_setting"){
        Update_Admin($_POST["user_name"],$_POST["user_login"],$_POST["user_old_password"],$_POST["user_new_password"],$_POST["user_replay_new_password"]);
    }
    elseif ($_POST["form"]=="add_item_form"){
        if($_POST["current_li"]=="Пользователь") {
            Add_User($_POST["users_name"],$_POST["users_password"],$_POST["users_email"],$_POST["phone_number"],$_POST["country_name"],$_POST["city_name"]);
        }
        elseif($_POST["current_li"]=="Компания"){
            Add_Company($_POST["users_name"],$_POST["users_password"],$_POST["users_email"],$_POST["phone_number"],$_POST["country_name"],$_POST["city_name"]);
        }
    }
}
elseif (isset($_POST["operation"])){
    if ($_POST["operation"]=="delete") {
        Delete_Object($_POST["current_li"], $_POST["item_id"]);
    }
    elseif ($_POST["operation"]=="edit_item"){
        Edit_Object($_POST["current_li"],$_POST["item_id"]);
    }
    elseif ($_POST["operation"]=="confirm"){
        Confirm_Photo($_POST["item_id"]);
    }
}
elseif (isset($_POST["search_form"])){
    print_r($_POST);
}

function Update_Admin($user_name,$user_log,$user_old_pass,$user_new_pass,$user_replay_new_pass){
    $result = mysqli_fetch_assoc(sql_select("SELECT `admin_name`,`admin_password` 
                          FROM `admins` 
                          WHERE `admin_name`='{$user_name}'"));
    if ($result['admin_password']=$user_old_pass && $user_new_pass==$user_replay_new_pass){
        $update = sql_update("UPDATE `admins` SET `admin_login` = '{$user_log}', 
                              `admin_password`='{$user_new_pass}' 
                              WHERE `admin_name` = '{$user_name}'");
        if (!$update){
            echo("Ошибка! Изменения не сохранены");
        }
        else{
            echo("Изменения сохранены");
        }
    }
} 
function Add_User($user_name,$user_pass,$user_email,$user_phone,$user_country,$user_city){
    $id_city = Get_Id_City($user_city,$user_country);
    if (mysqli_fetch_assoc(Get_Validation($user_name,$user_email))) {
        echo "Ошибка! Имя и/или email уже заняты";
        return;
    }
    $add_update = sql_update("INSERT INTO `users` (`users_name`,`users_password`,`users_email`,`phone_number`,`type_user`,`id_city`) 
                              VALUES ('{$user_name}','{$user_pass}','{$user_email}','{$user_phone}','User','$id_city')");
    if (!$add_update){
        echo("Ошибка! Пользователь не добавлен");
    }
    else{
        echo("Пользователь добавлен");
    }
}

function Add_Company($user_name,$user_pass,$user_email,$user_phone,$user_country,$user_city){
    $id_city = Get_Id_City($user_city,$user_country);
    if (mysqli_fetch_assoc(Get_Validation($user_name,$user_email))) {
        echo "Ошибка! Имя или(и) email уже заняты";
        return;
    }
    $add_update = sql_update("INSERT INTO `users` (`users_name`,`users_password`,`users_email`,`type_user`,`id_city`,`phone_number`) 
                              VALUES ('{$user_name}', '{$user_pass}','{$user_email}','Company','$id_city','{$user_phone}')");
    if (!$add_update){
        echo("Ошибка! Компания не добавлена");
    }
    else{
        echo("Компания добавлена");
    }
}

function Edit_Object($current_li,$item_id){
    if ($current_li=="Пользователи" || $current_li=="Компании"){
        $id_city = Get_Id_City($_POST["city_name"],$_POST["country_name"]);
        $update_user = sql_update("UPDATE `users` SET `users_name`='{$_POST["users_name"]}',`users_password`='{$_POST["users_password"]}',`users_email`='{$_POST["users_email"]}',
                              `id_city`='{$id_city}',`phone_number`=".(strlen($_POST["phone_number"]) != 13 ? 'NULL':"'{$_POST["phone_number"]}'")." WHERE `id_user` = '$item_id'");
        if (!$update_user)
            echo "Ошибка! Информация не обновлена";
        else
            echo "Информация обновлена";
    }
    elseif ($current_li=="Фотографии"){
        $id_author = Get_Id_Author($_POST["users_name"],$_POST["type_user"]);
        $id_event = Get_Id_Event($_POST["Source"],$_POST["type_data"]);
        $coordinates=explode(' ',$_POST["NE"]);
        if (strpos($id_author,"Ошибка!")){
            echo $id_author;
        }
        else{
            $update_item = sql_update("UPDATE `image` SET `id_author`='{$id_author}',`description_image`='{$_POST["description_image"]}',`publication_date`='{$_POST["publication_date"]}',
                                  `id_event`='{$id_event}',`image_N`='{$coordinates[0]}',`image_E`='{$coordinates[1]}' WHERE `id_image` = '{$item_id}'");
            if (!$update_item){
                echo "Ошибка! Информация не обновлена";
            }
            else{
                echo "Информация обновлена";
            }
        }
    }
}

function Delete_Object($current_li, $item_id){
    if($current_li=="Акции"){
        $delete_item = sql_update("DELETE FROM `stock` WHERE `id_stock`='{$item_id}'");
        if (!$delete_item){
            echo "Ошибка! Акция не удалена";
        }
        else{
            echo("Акция удалена");
        }
    }
    elseif ($current_li=="Фотографии" || $current_li=="Модерация"){
        $delete_item = sql_update("DELETE FROM `image` WHERE `id_image`='{$item_id}'");
        if (!$delete_item){
            echo "Ошибка! Фотография не удалена";
        }
        else{
            echo("Фотография удалена");
        }
    }
    else {
        $delete_item = sql_update("DELETE FROM `users` WHERE `id_user`='{$item_id}'");
        if ($current_li=="Пользователи"){
            if (!$delete_item){
                echo "Ошибка! Пользователь не удален";
            }
            else{
                echo("Пользователь удален");
            }
        }
        elseif ($current_li=="Компании"){
            if (!$delete_item){
                echo "Ошибка! Компания не удалена";
            }
            else{
                echo("Компания удалена");
            }
        }
    }
}

function Confirm_Photo($item_id){
    $update = sql_update("UPDATE `image` SET `isConfirmed` = '1'
                        WHERE `id_image` = '{$item_id}'");
    if (!$update){
        echo("Ошибка! Изменения не сохранены");
    }
    else{
        echo("Изменения сохранены");
    }
}

function Get_Validation($user_name, $user_email){
    return sql_select("SELECT `id_user` as 'is_invalid' FROM `users` WHERE `users_name` = '$user_name' OR `users_email`='$user_email' ");
}