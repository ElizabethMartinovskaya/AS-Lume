<?php
require_once 'sql_function.php';
if (isset($_POST["get_add_form"])) {
    $list = array(
        'Пользователь' => array(
            'users_name' => 'Имя',
            'users_password' => 'Пароль',
            'users_email' => 'e-mail',
            'phone_number' => 'Номер телефона',
            'country_name' => 'Страна',
            'city_name' => 'Город'
        ),
        'Компания' => array(
            'users_name' => 'Название',
            'users_password' => 'Пароль',
            'users_email' => 'e-mail',
            'phone_number' => 'Номер телефона',
            'country_name' => 'Страна',
            'city_name' => 'Город'
        ),
        'Акция' => array(
            'stock_name' => 'Название',
            'stock_type' => 'Тип',
            'id_author' => 'Автор',
            'description_stock' => 'Описание',
            'date_begin' => 'Дата начала',
            'date_end' => 'Дата окончания',
            'prize_id' => 'Приз'
        ),
        'Фотография' => array(
            'Image_src' => 'Фото',
            'description_image' => 'Описание',
            'width_image'=>'Ширина фото',
            'height_image'=>'Высота фото',
            'Source' => 'Ресурс',
            'type_data'=>'Тип ресурса',
            'image_NE' => 'Координаты'
        )
    );
    $input_result = "";
    if ($_POST["get_add_form"] == "Пользователь" || $_POST["get_add_form"] == "Компания") {
        foreach ($list[$_POST["get_add_form"]] as $key => $item) {
            if ($key == "phone_number") {
                $input_result .= get_input_empty("phone_empty",$key, $item);
            }
            else {
                $input_result .= get_input_empty("text_empty",$key, $item);
            }
        };
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
    $id = $_POST["id"];
    $list = (array)json_decode($_POST["list"]);
    if ($current_li == "Пользователи" || $current_li =="Компании") {
        $result = mysqli_fetch_assoc(sql_select("SELECT `id_user`,`users_name`,`users_password`,`users_email`,`phone_number`, `country_name`,`city_name`
                              FROM `users`,`city`,`country`
                              WHERE id_user='$id'
                              AND `users`.`id_city`=`city`.`id_city`
                              AND `city`.`id_country`=`country`.`id_country`"));
    }
    elseif ($current_li == "Акции") {
        $result = mysqli_fetch_assoc(sql_select("SELECT `id_stock`,`stock_name`,`stock_type`,`users_name`,`description_stock`,`date_begin`,`date_end`,`prize_description`
                                               FROM `users`,`stock`,`stock_type`,`stock_prize`,`prize`
                                               WHERE `id_stock`='$id'
                                               `stock`.`id_stock_type`=`stock_type`.`id_stock_type`
                                               AND `stock`.`id_author`=`users`.`id_user`
                                               AND `stock`.`id_stock`=`stock_prize`.`Stock_id_stock`
                                               AND `stock_prize`.`id_prize`=`prize`.`id_prize`"));
    }
    echo "<a href='#popup_window' id ='click_edit'>Изменить</a>
              <a href='#overlay' class='overlay' id='popup_window'></a>
              <div class='popup'>";
    echo "<form id='edit_form'>";
    foreach ($list[$current_li] as $key => $item)
        if ($key == "phone_number") {
            $input_result .= get_input_full("phone",$key,$item,$result);
        }
        else {
            $input_result .= get_input_full("text",$key,$item,$result);
        }
    echo $input_result;
    echo "<div class='form_item'><input type='submit' name='edit_item' value='Изменить'></div>";
    echo "</form>";
    echo "<a class='close' title='Закрыть' href='#close'></a></div>";
}
elseif (isset($_POST['dialog_text'])){
    get_text_message($_POST['dialog_text'], $_POST['type']);
}
function get_input_empty($object, $key, $item){
    if ($object == "text_empty"){
        return "<div class='form_item'><label for='input_$key'>$item:</label><input type='text' name=$key id='input_$key'></div>";
    }
    elseif ($object == "phone_empty"){
        return "<div class='form_item'><label for='input_$key'>$item:</label><input type='text' name=$key id='input_$key pattern='^\+375(17|29|33|44)[0-9]{7}$'></div>";
    }
    elseif ($object == "date_empty"){
        return "<div class='form_item'><label for='input_$key'>$item:</label><input type='datetime-local' name=$key id='input_$key'></div>";
    }
    elseif($object == "photo"){
        return "<div class='form_item upload_photo'><label for='input_$key'>$item:</label><input type='file' name=$key id='input_$key'></div>";
    }
}
function get_input_full($object, $key, $item, $result){
    if ($object=="text_full"){
        return "<div class='form_item'><label for='input_ $key '>$item:</label><input type='text' name=$key  id='input_$key' value = '{$result[$key]}'></div>";
    }
    elseif ($object=="phone_full"){
        return "<div class='form_item'><label for='input_$key'> $item:</label><input type='text' name=$key id='input_$key' pattern='^\+375(17|29|33|44)[0-9]{7}$' value='{$result[$key]}'></div>";
    }
}
function get_text_message($text,$type){
    if ($type=="message"){
        echo "<div id='dialog' title='Сообщение'>
            <p>{$text}</p>
          </div>";
    }
    elseif ($type=="dialog"){
        echo "<div id='dialog_window' title='Подтверждение'>
            <p>{$text}</p>
          </div>";
    }
}