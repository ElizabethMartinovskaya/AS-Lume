<?php
$db = mysqli_connect(
    'lume.datacenter.by:3306',
    'lume',
    'qweasdzxc123',
    'lume_Lume') or die("Ошибка подключения базы данных" . mysqli_error($db));
mysqli_set_charset($db,"utf8");
function sql_select($sql){
    global $db;
    return mysqli_query($db, $sql);
}
function sql_update($sql){
    global $db;
    return $db -> query($sql);
}