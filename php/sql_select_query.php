<?php
require_once 'sql_functions.php';
function get_data_from_db($current_li){
    if ($current_li == 'Пользователи'){
        $result = (sql_select("SELECT `id_user`,`users_name`,`users_password`,`users_email`,`phone_number`, `country_name`,`city_name`,`users_N`,`users_E`,
get_all_stocks_users(`users`.`id_user`) as 'all_stock_users_count',get_now_stocks_users(`users`.`id_user`) as 'now_stock_users_count',
get_do_stock_users(`users`.`id_user`) as 'do_stock_users_count', get_scan_photos_users(`users`.`id_user`) as 'scan_photos_users_count'
                                                     FROM `users`,`city`,`country`
                                                     WHERE `type_user`='user' 
                                                     AND `users`.`id_city`=`city`.`id_city`
                                                     AND `city`.`id_country`=`country`.`id_country`"));
    }
    elseif ($current_li == 'Компании'){
        $result = (sql_select("SELECT `id_user`,`users_name`,`users_password`,`users_email`,`phone_number`, `country_name`,`city_name`,
get_all_stocks_company(`users`.`id_user`) as 'all_stock_company_count',get_now_stocks_company(`users`.`id_user`) as 'now_stock_company_count',
get_do_stocks_company(`users`.`id_user`) as 'do_stock_company_count', get_download_photos_company(`users`.`id_user`) as 'download_photos_company_count'
                                                     FROM `users`,`city`,`country` 
                                                     WHERE `type_user`='company'
                                                     AND `users`.`id_city`=`city`.`id_city`
                                                     AND `city`.`id_country`=`country`.`id_country`"));
    }
    elseif ($current_li == 'Акции') {
        $result = (sql_select("SELECT `id_stock`,`stock_name`,`stock_type`,`users_name`,`description_stock`,`date_begin`,`date_end`,`prize_description`,
get_participants_stocks(`stock`.`id_stock`) as 'participants_stock_count'
                                                     FROM `users`,`stock`,`stock_type`,`stock_prize`,`prize`
                                                     WHERE `stock`.`id_stock_type`=`stock_type`.`id_stock_type`
                                                     AND `stock`.`id_author`=`users`.`id_user`
                                                     AND `stock`.`id_stock`=`stock_prize`.`Stock_id_stock`
                                                     AND `stock_prize`.`id_prize`=`prize`.`id_prize`"));
    }
    elseif ($current_li == 'Фотографии') {
        $result = (sql_select("SELECT `id_image`,`Image_src`,`users_name`,`type_user`,`description_image`,`width_image`,`height_image`,`Source`,`type_data`,`publication_date`,`image_N`,`image_E`, `isConfirmed`
                                                     FROM `users`,`image`,`event`,`type_of_data`,`properties_images`
                                                     WHERE `users`.`id_user`=`image`.`id_author`
                                                     AND `image`.`id_event`=`event`.`id_event`
                                                     AND `event`.`Type_id_Type`=`type_of_data`.`id_Type`
                                                     AND `image`.`id_properties`=`properties_images`.`id_propertie`
                                                     AND `image`.`isConfirmed`='1'"));
    }
    elseif ($current_li == "Модерация"){
        $result = (sql_select("SELECT `id_image`,`Image_src`,`users_name`,`type_user`,`description_image`,`width_image`,`height_image`,`Source`,`type_data`,`publication_date`,`image_N`,`image_E`, `isConfirmed`
                                                     FROM `users`,`image`,`event`,`type_of_data`,`properties_images`
                                                     WHERE `users`.`id_user`=`image`.`id_author`
                                                     AND `image`.`id_event`=`event`.`id_event`
                                                     AND `event`.`Type_id_Type`=`type_of_data`.`id_Type`
                                                     AND `image`.`id_properties`=`properties_images`.`id_propertie`
                                                     AND `image`.`isConfirmed`='0'"));
    }
    return $result;
}