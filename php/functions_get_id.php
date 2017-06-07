<?php
function Get_Id_City($city_name, $country_name){
    $id_country = sql_select("SELECT `id_country` FROM `country`
                              WHERE `country_name`='{$country_name}'");
    if ($id_country = mysqli_fetch_assoc($id_country)) {
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
    else {
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

function Get_Id_Author($users_name,$type_user){
    $id_author = sql_select("SELECT `id_user` FROM `users`
                            WHERE `users_name`='{$users_name}'
                            AND `type_user`='{$type_user}'");
    $id_author = mysqli_fetch_assoc($id_author);
    if (!$id_author){
        return "Ошибка! Автора не существует. Пожалуйста, добавьте сначала автора";
    }
    else{
        return $id_author['id_user'];
    }
}

function Get_Id_Properties_Image($width_image,$height_image){
    $id_properties_image = sql_select("SELECT `id_propertie` FROM `properties_images`
                                      WHERE `width_image`='{$width_image}'
                                      AND `height_image`='{$height_image}'");
    $id_properties_image = mysqli_fetch_assoc($id_properties_image);
    if (!$id_properties_image) {
        sql_update("INSERT INTO `properties_images` (`width_image`,`height_image`) 
                  VALUES ('{$width_image}', '{$height_image}')");
        $id_properties_image = mysqli_fetch_assoc(sql_select("SELECT `id_propertie` FROM `properties_images`
                                                                    WHERE `width_image`='{$width_image}' 
                                                                    AND `height_image` = '{$height_image}'"));
    }
    return $id_properties_image['id_propertie'];
}

function Get_Id_Event($Source,$type_data){
    $id_type = sql_select("SELECT `id_Type` FROM `type_of_data`
                          WHERE `type_data`='{$type_data}'");
    if ($id_type = mysqli_fetch_assoc($id_type)['id_Type']) {
        $result_id_event = sql_select("SELECT `id_event` FROM `event`
                                      WHERE `Source`='{$Source}' 
                                      AND `Type_id_Type` = '{$id_type}'");
        if(!($result_id_event= mysqli_fetch_assoc($result_id_event))) {
            sql_update("INSERT INTO `event` (`Source`, `Type_id_Type`) 
                      VALUES ('{$Source}', '{$id_type}')");
            $result_id_event = mysqli_fetch_assoc(sql_select("SELECT `id_event` FROM `event`
                                                            WHERE `Source`='{$Source}' 
                                                            AND `Type_id_Type` = '{$id_type}'"));
        }
    }
    else {
        sql_update("INSERT INTO `type_of_data` (`type_data`) 
                    VALUES ('{$type_data}')");
        $id_type = mysqli_fetch_assoc(sql_select("SELECT `id_Type` FROM `type_of_data`
                                                WHERE `type_data`='{$type_data}'"));
        sql_update("INSERT INTO `event` (`Source`, Type_id_Type) 
                    VALUES ('{$Source}', '{$id_type['id_Type']}')");
        $result_id_event = mysqli_fetch_assoc(sql_select("SELECT `id_event` FROM `event`
                                                       WHERE `Source`='{$Source}' 
                                                       AND Type_id_Type = '{$id_type['id_Type']}'"));
    }
    return $result_id_event['id_event'];
}