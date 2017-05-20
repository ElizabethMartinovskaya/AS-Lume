<?php
require_once 'sql_function.php';
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
        'Source'=>'Ресурс',
        'type_data'=>'Тип ресурса',
        'publication_date'=>'Дата публикации',
        'image_N'=>'Координаты_1',
        'image_E'=>'Координаты_2'
    )
);
$current_li=$_POST['current_li'];
if ($current_li=='Пользователи'){
    $result = (sql_select("SELECT `id_user`,`users_name`,`users_password`,`users_email`,`phone_number`, `country_name`,`city_name`,`users_N`,`users_E`,
get_all_stocks_users(`users`.`id_user`) as 'all_stock_users_count',get_now_stocks_users(`users`.`id_user`) as 'now_stock_users_count',
get_do_stock_users(`users`.`id_user`) as 'do_stock_users_count', get_scan_photos_users(`users`.`id_user`) as 'scan_photos_users_count'
                                                     FROM `users`,`city`,`country`
                                                     WHERE `type_user`='user' 
                                                     AND `users`.`id_city`=`city`.`id_city`
                                                     AND `city`.`id_country`=`country`.`id_country`")) or die("Ошибка" . mysqli_error($db));
}
elseif ($current_li=='Компании') {
    $result = (sql_select("SELECT `id_user`,`users_name`,`users_password`,`users_email`,`phone_number`, `country_name`,`city_name`,
get_all_stocks_company(`users`.`id_user`) as 'all_stock_company_count',get_now_stocks_company(`users`.`id_user`) as 'now_stock_company_count',
get_do_stocks_company(`users`.`id_user`) as 'do_stock_company_count', get_download_photos_company(`users`.`id_user`) as 'download_photos_company_count'
                                                     FROM `users`,`city`,`country` 
                                                     WHERE `type_user`='company'
                                                     AND `users`.`id_city`=`city`.`id_city`
                                                     AND `city`.`id_country`=`country`.`id_country`")) or die("Ошибка" . mysqli_error($db));
}
elseif ($current_li=='Акции') {
    $result = (sql_select("SELECT `id_stock`,`stock_name`,`stock_type`,`users_name`,`description_stock`,`date_begin`,`date_end`,`prize_description`,
get_participants_stocks(`stock`.`id_stock`) as 'participants_stock_count'
                                                     FROM `users`,`stock`,`stock_type`,`stock_prize`,`prize`
                                                     WHERE `stock`.`id_stock_type`=`stock_type`.`id_stock_type`
                                                     AND `stock`.`id_author`=`users`.`id_user`
                                                     AND `stock`.`id_stock`=`stock_prize`.`Stock_id_stock`
                                                     AND `stock_prize`.`id_prize`=`prize`.`id_prize`")) or die("Ошибка" . mysqli_error($db));
}
elseif ($current_li=='Фотографии') {
    $result = (sql_select("SELECT `id_image`,`users_name`,`type_user`,`Image_src`,`description_image`,`Source`,`type_data`,`publication_date`,`image_N`,`image_E`
                                                     FROM `users`,`image`,`event`,`type_of_data`
                                                     WHERE `users`.`id_user`=`image`.`id_author`
                                                     AND `image`.`id_event`=`event`.`id_event`
                                                     AND `event`.`Type_id_Type`=`type_of_data`.`id_Type`")) or die("Ошибка" . mysqli_error($db));
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
    if ($current_li=='Акции'){
        $id_item=$result_item['id_stock'];
    }
    elseif ($current_li=='Фотографии'){
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
