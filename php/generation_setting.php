<?php
require_once 'sql_function.php';
if (!isset($_SESSION))
    session_start();
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