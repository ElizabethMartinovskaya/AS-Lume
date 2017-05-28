<?php
function get_operation_btn($type_of_operation,$current_li,$name_item){
    if ($type_of_operation == 'Редактировать'){
        return "<a class='edit' id='edit_$name_item' data-title='Редактировать' onclick=\"ReturnIdForOperation('edit','$current_li','$name_item')\"></a>";
    }
    elseif ($type_of_operation == "Удалить"){
        return "<a class='delete' id='delete_$name_item' data-title='Удалить' onclick=\"ReturnIdForOperation('delete', '$current_li', '$name_item')\"></a></div>";
    }
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
