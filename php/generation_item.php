<?php
require_once 'sql_functions.php';
require_once 'data_of_item.php';
require_once 'get_html.php';
$input_result = "";
if (isset($_POST["get_add_form"])) {
    if ($_POST["get_add_form"] == "Пользователь" || $_POST["get_add_form"] == "Компания") {
        foreach ($list[$_POST["get_add_form"]] as $key => $item) {
            if ($key == "phone_number") {
                $input_result .= get_input_empty("phone_empty",$key, $item);
            }
            else {
                $input_result .= get_input_empty("text_empty",$key, $item);
            }
        }
    }
    elseif ($_POST["get_add_form"] == "Фотография"){
        foreach ($list[$_POST["get_add_form"]] as $key => $item) {
            if ($key == "Image_src"){
                $input_result .= get_input_empty("photo", $key, $item);
            }
            else {
                $input_result .= get_input_empty("text_empty",$key, $item);
            }
        }
    }
    echo $input_result;
}
elseif (isset($_POST["create_edit_form"])) {
    $current_li = $_POST["create_edit_form"];
    $name = $_POST["name"];
    $edit_list = (array)json_decode($_POST["edit_list"]);
    if ($current_li == "Пользователи" || $current_li =="Компании") {
        $result = mysqli_fetch_assoc(sql_select("SELECT `id_user`,`users_name`,`users_password`,`users_email`,`phone_number`, `country_name`,`city_name`
                              FROM `users`,`city`,`country`
                              WHERE `users_name`='$name'
                              AND `users`.`id_city`=`city`.`id_city`
                              AND `city`.`id_country`=`country`.`id_country`"));
    }
    elseif ($current_li == "Акции") {
        $result = mysqli_fetch_assoc(sql_select("SELECT `id_stock`,`stock_name`,`stock_type`,`users_name`,`description_stock`,`date_begin`,`date_end`,`prize_description`
                                               FROM `users`,`stock`,`stock_type`,`stock_prize`,`prize`
                                               WHERE `stock_name`='$name'
                                               AND `stock`.`id_stock_type`=`stock_type`.`id_stock_type`
                                               AND `stock`.`id_author`=`users`.`id_user`
                                               AND `stock`.`id_stock`=`stock_prize`.`Stock_id_stock`
                                               AND `stock_prize`.`id_prize`=`prize`.`id_prize`"));
    }
    echo "<a href='#popup_window' id ='click_edit'>Изменить</a>
              <a href='#overlay' class='overlay' id='popup_window'></a>
              <div class='popup'>";
    echo "<form id='edit_form'>";
    foreach ($edit_list[$current_li] as $key => $item){
        if ($key == "phone_number") {
            $input_result .= get_input_full("phone_full",$key,$item,$result);
        }
        else {
            $input_result .= get_input_full("text_full",$key,$item,$result);
        }
    }
    echo $input_result;
    echo "<div class='form_item'><input type='submit' name='edit_item' value='Изменить'></div>";
    echo "</form>";
    echo "<a class='close' title='Закрыть' href='#close'></a></div>";
}
elseif (isset($_POST['dialog_text'])){
    get_text_message($_POST['dialog_text'], $_POST['type']);
}
