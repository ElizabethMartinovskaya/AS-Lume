<?php
require_once 'sql_functions.php';
require_once 'generation_item.php';
require_once 'data_of_item.php';
require_once 'sql_select_query.php';
require_once 'get_html.php';
if (!isset($_SESSION))
    session_start();
if (isset($_POST['current_li'])) {
    $current_li=$_POST['current_li'];
    if ($current_li!=='Настройки'){
        if ($current_li == 'Пользователи'){
            $result = get_data_from_db($current_li);
        }
        elseif ($current_li == 'Компании') {
            $result = get_data_from_db($current_li);
        }
        elseif ($current_li == 'Акции') {
            $result = get_data_from_db($current_li);
        }
        elseif ($current_li == 'Фотографии') {
            $result = get_data_from_db($current_li);
        }
        elseif ($current_li == "Модерация"){
            $result = get_data_from_db($current_li);
        }
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
                $name_item=$result_item['stock_name'];
            }
            elseif ($current_li=="Фотографии" || $current_li=="Модерация"){
                $name_item=$result_item['Image_src'];
            }
            else{
                $name_item=$result_item['users_name'];
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
            if ($current_li == 'Пользователи' || $current_li == 'Компании' || $current_li == 'Фотографии'){
                echo get_operation_btn("Редактировать",$current_li,$name_item);
                echo get_operation_btn("Удалить",$current_li,$name_item);
            }
            else{
                echo get_operation_btn("Удалить",$current_li,$name_item);
            }
            echo "</div>";
        }
        echo "</div>";
    }
    else {
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
}
elseif (isset($_POST['get_checkbox_list'])){
    $current_li=$_POST['get_checkbox_list'];
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
        $result = get_data_from_db($current_li);;
    } elseif ($current_li == 'Компании') {
        $result = get_data_from_db($current_li);;
    } elseif ($current_li == 'Акции') {
        $result = get_data_from_db($current_li);;
    } elseif ($current_li == 'Фотографии') {
        $result = get_data_from_db($current_li);;
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
            $name_item = $result_item['stock_name'];
        } elseif ($current_li == 'Фотографии') {
            $name_item = $result_item['Image_src'];
        } else {
            $name_item = $result_item['users_name'];
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
            $row .= get_operation_btn("Редактировать",$current_li,$name_item);
            $row .= get_operation_btn("Удалить",$current_li,$name_item);
        } else {
            $row .= get_operation_btn("Удалить",$current_li,$name_item);
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