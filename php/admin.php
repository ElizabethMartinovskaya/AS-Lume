<?php
session_start();
if (isset($_SESSION['id_Admin'])&&(isset($_SESSION['admin_name']))){
    echo "<script type='text/javascript' src='../js/JQuery.js'></script>";
    echo "<script type='text/javascript' src='../js/functions.js'></script>";
    echo "<script type='text/javascript' src='../js/jquery.validate.min.js'></script>";
    echo "<script type='text/javascript' src='../js/jquery-ui-1.12.1/jquery-ui.min.js'></script>";
    echo "<link href='../css/admin_style.css' rel='stylesheet' type='text/css'>";
    echo "<link href='../js/jquery-ui-1.12.1/jquery-ui.theme.min.css' rel='stylesheet' type='text/css'>";
    echo "<link href='../js/jquery-ui-1.12.1/jquery-ui.min.css' rel='stylesheet' type='text/css'>";
    require_once 'sql_functions.php';
    require_once 'functions.php';
    $last_visit = mysqli_fetch_assoc(sql_select("SELECT `last_visit` 
                                         FROM `admins` WHERE `id_Admin` = '{$_SESSION['id_Admin']}' 
                                         AND `admin_name` = '{$_SESSION['admin_name']}'"))['last_visit'] or die("Ошибка" . mysqli_error($db));
    echo "<!DOCTYPE html>
          <html lang='ru'>
          <head>
                <meta charset='UTF-8'>
                <title>AS Lume</title>
          </head>
          <body>
                <header>
                <div class='left_info'>
                    <h1>adminLume</h1>
                </div>
                <div class='right_info'>                  
                    <div class='auser_info'>
                        <p>Добро пожаловать, <span>{$_SESSION['admin_name']}</span></p>
                        <p>Ваш последний визит: {$last_visit}</p>
                    </div>
                    <a href='?exit'></a>
                 </div>
                </header>
                <div class='nav_menu'>
                <ul id='menu'>
                    <li><div class='view_menu_item icon1'></div><a href='#users'><h1>Пользователи</h1></a></li>  
                    <li data-function='add'><div class='view_menu_item icon0'></div><a href='#add_user'><h1>Пользователь</h1></a></li>
                    <li><div class='view_menu_item icon2'></div><a href='#company'><h1>Компании</h1></a></li>
                    <li data-function='add'><div class='view_menu_item icon0'></div><a href='#add_company'><h1>Компания</h1></a></li>
                    <li><div class='view_menu_item icon3'></div><a href='#photos'><h1>Фотографии</h1></a></li>
                    <li data-function='add'><div class='view_menu_item icon0'></div><a href='#add_photo'><h1>Фотография</h1></a></li>
                    <li><div class='view_menu_item icon4'></div><a href='#stocks'><h1>Акции</h1></a></li> 
                    <li><div class='view_menu_item icon5'></div><a href='#moderation'><h1>Модерация</h1></a></li>
                    <li><div class='view_menu_item icon6'></div><a href='#settings'><h1>Настройки</h1></a></li>
                </ul>
                </div>
                <section>
                    <div class='block_info'>
                        <div class='header_info'>
                            <h1></h1>
                            <div class='search'></div>
                            <div class='choose_menu'></div>    
                        </div>                   
                        <div class='list_info'></div>
                        <div class='window'></div>
                    </div>
                </section>
          </body>
          </html>";
    if (isset($_GET['exit'])){
        $date = date('Y-m-d H:i:s');
        sql_update("UPDATE `admins` SET `last_visit` = '$date' WHERE `id_Admin` = '{$_SESSION['id_Admin']}' 
                      AND `admin_name` = '{$_SESSION['admin_name']}'")or die("Ошибка" . mysqli_error($db));
        unset($_SESSION['id_Admin']);
        unset($_SESSION['admin_name']);
        session_destroy();
        header('location:lock.php');
    }
}
else{
    header('location:lock.php');
}

