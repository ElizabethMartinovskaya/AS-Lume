<?php
require_once 'sql_functions.php';
require_once 'data_of_item.php';
require_once 'get_html.php';
$input_result = "";
if (isset($_POST["get_add_form"])) {
    if ($_POST["get_add_form"] == "Пользователь" || $_POST["get_add_form"] == "Компания") {
        foreach ($list[$_POST["get_add_form"]] as $key => $item) {
            if ($key == "phone_number") {
                $input_result .= get_item_empty("phone_empty",$key, $item);
            }
            else {
                $input_result .= get_item_empty("text_empty",$key, $item);
            }
        }
    }
    elseif ($_POST["get_add_form"] == "Фотография"){
        foreach ($list[$_POST["get_add_form"]] as $key => $item) {
            if ($key == "Image_src"){
                $input_result .= get_item_empty("photo_empty", $key, $item);
            }
            elseif ($key =="NE"){
                $input_result .= get_item_empty("coordinates_empty", $key, $item);
            }
            else{
                $input_result .= get_item_empty("text_empty",$key, $item);
            }
        }
    }
    echo $input_result;
}
elseif (isset($_POST["create_edit_form"])) {
    $current_li = $_POST["create_edit_form"];
    $item_id = $_POST["item_id"];
    $edit_list = (array)json_decode($_POST["edit_list"]);
    if ($current_li == "Пользователи" || $current_li =="Компании") {
        $result = mysqli_fetch_assoc(sql_select("SELECT `id_user`,`users_name`,`users_password`,`users_email`,`phone_number`, `country_name`,`city_name`,`users_N`,`users_E`
                              FROM `users`,`city`,`country`
                              WHERE `id_user`='{$item_id}'
                              AND `users`.`id_city`=`city`.`id_city`
                              AND `city`.`id_country`=`country`.`id_country`"));
        $coordinates = $result["users_N"].' '.$result["users_E"];
    }
    elseif ($current_li == "Акции") {
        $result = mysqli_fetch_assoc(sql_select("SELECT `id_stock`,`stock_name`,`stock_type`,`users_name`,`description_stock`,`date_begin`,`date_end`,`prize_description`
                                               FROM `users`,`stock`,`stock_type`,`stock_prize`,`prize`
                                               WHERE `id_stock`='{$item_id}'
                                               AND `stock`.`id_stock_type`=`stock_type`.`id_stock_type`
                                               AND `stock`.`id_author`=`users`.`id_user`
                                               AND `stock`.`id_stock`=`stock_prize`.`Stock_id_stock`
                                               AND `stock_prize`.`id_prize`=`prize`.`id_prize`"));
    }
    elseif ($current_li=="Фотографии"){
        $result = mysqli_fetch_assoc(sql_select("SELECT `id_image`,`Image_src`,`users_name`,`type_user`,`description_image`,`width_image`,`height_image`,`Source`,`type_data`,`publication_date`,`image_N`,`image_E`, `isConfirmed`
                                                     FROM `users`,`image`,`event`,`type_of_data`,`properties_images`
                                                     WHERE `id_image`='{$item_id}'
                                                     AND `users`.`id_user`=`image`.`id_author`
                                                     AND `image`.`id_event`=`event`.`id_event`
                                                     AND `event`.`Type_id_Type`=`type_of_data`.`id_Type`
                                                     AND `image`.`id_properties`=`properties_images`.`id_propertie`
                                                     AND `image`.`isConfirmed`='1'"));
        $coordinates = $result["image_N"].' '.$result["image_E"];
    }
    echo "<a href='#popup_window' id ='click_edit'>Изменить</a>
              <a href='#overlay' class='overlay' id='popup_window'></a>";
    if ($current_li=="Фотографии"){
        echo "<div class='popup edit_image'>";
    }
    else{
        echo "<div class='popup'>";
    }
    echo "<form id='edit_form'>";
    foreach ($edit_list[$current_li] as $key => $item){
        if ($key=="Image_src"){
            $input_result .= get_item_full("photo_full",$key,$item,$result[$key]);
        }
        elseif ($key=="phone_number") {
            $input_result .= get_item_full("phone_full",$key,$item,$result[$key]);
        }
        elseif ($key=="publication_date"){
            $input_result .= get_item_full("date_full",$key,$item,$result[$key]);
        }
        elseif ($key=="NE"){
            $input_result .= get_item_full("coordinates_full","NE","Координаты",$coordinates);
        }
        elseif ($key=="type_user"){
            $input_result .= get_item_full("type_user_full",$key,$item,$result[$key]);
        }
        else {
            $input_result .= get_item_full("text_full",$key,$item,$result[$key]);
        }
    }
    echo $input_result;
    echo "<div class='form_item'><input type='submit' name='edit_item' value='Изменить'></div>";
    echo "</form>";
    if ($current_li=="Фотографии"){
        echo "<div class='map_edit' id='map_canvas_edit'></div>";
    }
    echo "<a class='close' title='Закрыть' href='#close'></a></div>";
}
elseif (isset($_POST['dialog_text'])){
    get_text_message($_POST['dialog_text'], $_POST['type']);
}
