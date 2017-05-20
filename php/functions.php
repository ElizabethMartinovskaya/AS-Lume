<?php
require_once 'sql_function.php';
require_once 'generation_item.php';
if (!isset($_SESSION))
    session_start();
$list = array (
    "Пользователи" => array(
        'users_name'=> 'Имя',
        'users_password'=> 'Пароль',
        'users_email'=> 'e-mail',
        'phone_number'=> 'Номер телефона',
        'country_name'=>'Страна',
        'city_name'=>'Город',
        'all_stock_users_count'=>'Все акции',
        'now_stock_users_count'=>'Текущие акции',
        'do_stock_users_count'=>'Выполненные акции',
        'scan_photos_users_count'=>'Отсканированные фото'
    ),
    "Компании" => array(
        'users_name'=>'Название',
        'users_password'=>'Пароль',
        'users_email'=>'e-mail',
        'phone_number'=>'Номер телефона',
        'country_name'=> 'Страна',
        'city_name'=> 'Город',
        'all_stock_company_count'=>'Все акции',
        'now_stock_company_count'=>'Текущие акции',
        'do_stock_company_count'=>'Оконченные акции',
        'download_photos_company_count'=>'Загруженные фото'
    ),
    "Акции" => array(
        'stock_name'=>'Название',
        'stock_type'=>'Тип',
        'users_name'=>'Автор',
        'description_stock'=>'Описание',
        'date_begin'=>'Дата начала',
        'date_end'=>'Дата окончания',
        'prize_description'=>'Приз',
        'participants_stock_count'=>'Участники'
    ),
    "Фотографии" => array(
        'Image_src'=>'Фото',
        'users_name'=>'Автор',
        'type_user'=>'Тип автора',
        'description_image'=>'Описание',
        'width_image'=>'Ширина фото',
        'height_image'=>'Высота фото',
        'Source'=>'Ресурс',
        'type_data'=>'Тип ресурса',
        'publication_date'=>'Дата публикации',
        'image_NE'=>'Координаты'
    ),
    "Модерация" => array (
        'Image_src'=>'Фото',
        'users_name'=>'Автор',
        'type_user'=>'Тип автора',
        'description_image'=>'Описание',
        'width_image'=>'Ширина фото',
        'height_image'=>'Высота фото',
        'category_image'=>'Категория фото',
        'Source'=>'Ресурс',
        'type_data'=>'Тип ресурса',
        'publication_date'=>'Дата публикации',
        'image_NE'=>'Координаты'
    )
);
if (isset($_POST['current_li'])) {
    $current_li=$_POST['current_li'];
    if ($current_li == 'Пользователи'){
        $result = (sql_select("SELECT `id_user`,`users_name`,`users_password`,`users_email`,`phone_number`, `country_name`,`city_name`,`users_N`,`users_E`,
get_all_stocks_users(`users`.`id_user`) as 'all_stock_users_count',get_now_stocks_users(`users`.`id_user`) as 'now_stock_users_count',
get_do_stock_users(`users`.`id_user`) as 'do_stock_users_count', get_scan_photos_users(`users`.`id_user`) as 'scan_photos_users_count'
                                                     FROM `users`,`city`,`country`
                                                     WHERE `type_user`='user' 
                                                     AND `users`.`id_city`=`city`.`id_city`
                                                     AND `city`.`id_country`=`country`.`id_country`"));
    }
    elseif ($current_li == 'Компании') {
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
    elseif ($current_li=='Настройки'){
        echo
        "<form id='user_setting'>
                    <div class='form_item'>
                        <label for='user_name'>Имя:</label><br>
                        <input type='text' id='user_name' name='user_name' value='{$_SESSION['admin_name']}' disabled>
                    </div>
                    <div class='form_item'>
                        <label for='user_login'>Логин: </label><br>
                        <input type='text' id='user_login' name='user_login' value={$_SESSION['admin_login']}>
                    </div>
                    <div class='form_item'>
                        <label for='user_password'>Старый пароль: </label><br>
                        <a class='show' href='#' onclick=\"ShowHidePassword('user_old_password')\"></a>
                        <input type='password' id='user_old_password' name='user_old_password'>                       
                    </div>
                    <div class='form_item'>
                        <label for='user_password'>Новый пароль: </label><br>
                        <a class='show' href='#' onclick=\"ShowHidePassword('user_new_password')\"></a>
                        <input type='password' id='user_new_password' name='user_new_password'>                     
                    </div>
                    <div class='form_item'>
                        <label for='user_password'>Повторите новый пароль: </label><br>
                        <a class='show' href='#' onclick=\"ShowHidePassword('user_replay_new_password')\"></a>
                        <input type='password' id='user_replay_new_password' name='user_replay_new_password'>                       
                    </div>
                    <div class='form_item'>
                        <input type='submit' name='user_update' value='Сохранить изменения'>
                    </div>
                </form>";
    }
    if ($current_li!='Настройки'){
        echo "<div class='list_table'>";
        $table=$list[$current_li];
        $table['options']= 'Действие';
        echo "<div class='list_table_header'>";
        foreach ($table as $key => $item) {
            echo "<div class='list_table_header_item $key'>$item</div>";
        }
        echo "</div>";
        while($result_item = mysqli_fetch_assoc($result)){
            if ($current_li=="Акции"){
                $id_item=$result_item['id_stock'];
            }
            elseif ($current_li=="Фотографии" || $current_li=="Модерация"){
                $id_item=$result_item['id_image'];
            }
            else{
                $id_item=$result_item['id_user'];
            }
            echo "<div class='list_row'>";
            $count_item=0;
            foreach ($result_item as $key => $item){
                $count_item++;
                if ($count_item!==1){
                    echo "<div class='list_cell $key'>";
                    echo $item == NULL? "" : $item;
                    echo "</div>";
                }
            }
            echo "<div class='list_cell options'>";
            if ($current_li=="Пользователи" || $current_li=="Компании"){
                echo "<a class='edit' id='edit_$id_item' data-title='Редактировать' onclick=\"ReturnIdForOperation('edit','$current_li','$id_item')\"></a>
                    <a class='delete' id='delete_$id_item' data-title='Удалить' onclick=\"ReturnIdForOperation('delete','$current_li','$id_item')\"></a></div>";
            }
            else{
                echo "<a class='delete' id='delete_$id_item' data-title='Удалить' onclick=\"ReturnIdForOperation('delete', '$current_li', '$id_item')\"></a></div>";
            }
            echo "</div>";
        }
        echo "</div>";
    }
}
elseif (isset($_POST['get_table_info'])){
    $current_li=$_POST['get_table_info'];
    if ($current_li!='Настройки') {
        $checkbox_result = "";
        foreach($list[$current_li] as $key => $item) {
            $checkbox_result .= "<div><input type='checkbox' value=$key id='checkbox_$key'><label for='checkbox_$key' onclick='data_query(this)'>$item</label></div>";
        };
        echo $checkbox_result;
    }
}
elseif (isset($_POST['get_search_form'])){
    $current_li=$_POST['get_search_form'];
    $search_result = "";
    echo "<form method='post' id='#search_form'>
          <input type='search' name='search' placeholder='Поиск...'>
          <select id='search_select'>
          <option>Все поля</option>";
    $option_result = "";
    foreach($list[$current_li] as $key => $item) {
        $option_result .= "<option value=$key> $item </option>";
    };
    echo $option_result;
    echo "</select>
          </form>";
}
elseif(isset($_POST["search_items"])) {
    $search_item = $_POST["search_items"];
    $current_li = $_POST['current_li_'];
    $search_field = $_POST["search_field"];
    $result_table ="";
    $count_suitable_item = 0;

    if ($current_li == 'Пользователи') {
        $result = (sql_select("SELECT `id_user`,`users_name`,`users_password`,`users_email`,`phone_number`, `country_name`,`city_name`,`users_N`,`users_E`,
get_all_stocks_users(`users`.`id_user`) as 'all_stock_users_count',get_now_stocks_users(`users`.`id_user`) as 'now_stock_users_count',
get_do_stock_users(`users`.`id_user`) as 'do_stock_users_count',get_scan_photos_users(`users`.`id_user`) as 'scan_photos_users_count'
                                                     FROM `users`,`city`,`country`
                                                     WHERE `type_user`='user'
                                                     AND `users`.`id_city`=`city`.`id_city`
                                                     AND `city`.`id_country`=`country`.`id_country`"));
    } elseif ($current_li == 'Компании') {
        $result = (sql_select("SELECT `id_user`,`users_name`,`users_password`,`users_email`,`phone_number`, `country_name`,`city_name`,
get_all_stocks_company(`users`.`id_user`) as 'all_stock_company_count',get_now_stocks_company(`users`.`id_user`) as 'now_stock_company_count',
get_do_stocks_company(`users`.`id_user`) as 'do_stock_company_count', get_download_photos_company(`users`.`id_user`) as 'download_photos_company_count'
                                                     FROM `users`,`city`,`country`
                                                     WHERE `type_user`='company'
                                                     AND `users`.`id_city`=`city`.`id_city`
                                                     AND `city`.`id_country`=`country`.`id_country`"));
    } elseif ($current_li == 'Акции') {
        $result = (sql_select("SELECT `id_stock`,`stock_name`,`stock_type`,`users_name`,`description_stock`,`date_begin`,`date_end`,`prize_description`,
get_participants_stocks(`stock`.`id_stock`) as 'participants_stock_count'
                                                     FROM `users`,`stock`,`stock_type`,`stock_prize`,`prize`
                                                     WHERE `stock`.`id_stock_type`=`stock_type`.`id_stock_type`
                                                     AND `stock`.`id_author`=`users`.`id_user`
                                                     AND `stock`.`id_stock`=`stock_prize`.`Stock_id_stock`
                                                     AND `stock_prize`.`id_prize`=`prize`.`id_prize`"));
    } elseif ($current_li == 'Фотографии') {
        $result = (sql_select("SELECT `id_image`,`users_name`,`type_user`,`description_image`,`Source`,`type_data`,`publication_date`,`image_N`,`image_E`
                                                     FROM `users`,`image`,`event`,`type_of_data`
                                                     WHERE `users`.`id_user`=`image`.`id_author`
                                                     AND `image`.`id_event`=`event`.`id_event`
                                                     AND `event`.`Type_id_Type`=`type_of_data`.`id_Type`"));
    }

    $result_table.= "<div class='list_table'>";
    $table = $list[$current_li];
    $table['options'] = 'Действие';
    $result_table.= "<div class='list_table_header'>";
    foreach ($table as $key => $item) {
        $result_table.= "<div class='list_table_header_item $key'>$item</div>";
    }
    $result_table.= "</div>";
    while ($result_item = mysqli_fetch_assoc($result)) {
        if ($current_li == 'Акции') {
            $id_item = $result_item['id_stock'];
        } elseif ($current_li == 'Фотографии') {
            $id_item = $result_item['id_image'];
        } else {
            $id_item = $result_item['id_user'];
        }
        $is_suitable = false;
        $row = "<div class='list_row'>";
        $count_item = 0;
        foreach ($result_item as $key => $item) {
            $count_item++;
            if ($count_item !== 1) {
                if (($key == $search_field || $search_field == "Все поля") && !$is_suitable)
                    if (strpos($item, $_POST["search_items"]) !== false)
                        $is_suitable = true;
                $row .= "<div class='list_cell $key'>";
                $row .= $item == NULL ? "" : $item;
                $row .= "</div>";
            }
        }
        $row .= "<div class='list_cell options'>";
        if ($current_li == "Пользователи" || $current_li == "Компании") {
            $row .= "<a class='edit' id='edit_$id_item' data-title='Редактировать' onclick=\"ReturnIdForOperation('edit','$current_li','$id_item')\"></a>
                    <a class='delete' id='delete_$id_item' data-title='Удалить' onclick=\"ReturnIdForOperation('delete','$current_li','$id_item')\"></a></div>";
        } else {
            $row .= "<a class='delete' id='delete_$id_item' data-title='Удалить' onclick=\"ReturnIdForOperation('delete', '$current_li', '$id_item')\"></a></div>";
        }
        $row .= "</div>";
        if ($is_suitable) {
            $result_table.= $row;
            $count_suitable_item++;
        }
    }
    $result_table.= "</div>";
    if ($count_suitable_item == 0)
        echo "По вашему запросу записей не найдено";
    else
        echo $result_table;
}