<?php
echo "<link href='../css/lock_style.css' rel='stylesheet' type='text/css'>";
require_once 'sql_function.php';
session_start();
if (isset($_SESSION['id_Admin'])&&(isset($_SESSION['admin_name']))) {
    header('location: admin.php');
}
else{
    echo "<!DOCTYPE html>
          <html lang='ru'>
          <head>
                <meta charset='UTF-8'>
                <title>Вход AS Lume</title>
          </head>
          <body>
                <div class='container'> 
                    <div class='entry_form'> 
                        <h1>adminLume</h1> 
                        <form action='' method='post'> 
                            <input type='text' name='admin_login' placeholder='Логин:' required='required'> 
                            <input type='text' name='admin_password' placeholder='Пароль:' required='required'> 
                            <input type='submit' name='entry' value='Войти'> 
                        </form>";
    if (isset($_POST['admin_login']) && isset($_POST['admin_password'])){
        $result =sql_select("SELECT `id_Admin`, `admin_name`,`admin_login`,`admin_password` 
                                         FROM `admins` WHERE `admin_login` = '" . mysqli_real_escape_string($db, $_POST['admin_login']) . "' 
                                         AND `admin_password` = '" . mysqli_real_escape_string($db, $_POST['admin_password']) . "' LIMIT 1") or die("Ошибка" . mysqli_error($db));
        if ($result && $user_info = mysqli_fetch_assoc($result)){
            $_SESSION['id_Admin'] = $user_info['id_Admin'];
            $_SESSION['admin_login'] = $user_info['admin_login'];
            $_SESSION['admin_password'] = $user_info['admin_password'];
            $_SESSION['admin_name'] = $user_info['admin_name'];
            header('location: admin.php');
        }
        else {
            echo "<div class='error'>Ошибка! Неверный логин или пароль.</div>";
        }
    }
                    echo "</div> 
                </div>
          </body>
          </html>";
}
