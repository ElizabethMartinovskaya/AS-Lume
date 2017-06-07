<?php
if (isset($_POST["Координаты"])){
    echo "<label for='input_NE'>Координаты:</label><input type='text' name='NE' id='input_NE' value='{$_POST["Координаты"]}' onclick=\"GetMap('edit', '{$_POST["Координаты"]}')\">";
}
function get_operation_btn($type_of_operation,$current_li,$name_item,$id_item){
    if ($type_of_operation == 'Редактировать'){
        return "<a class='edit' id='edit_$name_item' data-title='Редактировать' onclick=\"ReturnIdForOperation('edit','$current_li','$name_item','$id_item')\"></a>";
    }
    elseif ($type_of_operation == "Удалить"){
        return "<a class='delete' id='delete_$name_item' data-title='Удалить' onclick=\"ReturnIdForOperation('delete','$current_li','$name_item','$id_item')\"></a></div>";
    }
    elseif ($type_of_operation == "Подтвердить"){
        return "<a class='confirm' id='confirm_$name_item' data-title='Подтвердить' onclick=\"ReturnIdForOperation('confirm','$current_li','$name_item','$id_item')\"></a>";
    }
}
function get_item_empty($object, $key, $item){
    if ($object == "text_empty"){
        return "<div class='form_item'><label for='input_$key'>$item:</label><input type='text' name='$key' id='input_$key'></div>";
    }
    elseif ($object == "phone_empty"){
        return "<div class='form_item'><label for='input_$key'>$item:</label><input type='text' name='$key' id='input_$key pattern='^\+375(17|29|33|44)[0-9]{7}$'></div>";
    }
    elseif ($object == "date_empty"){
        return "<div class='form_item'><label for='input_$key'>$item:</label><input type='datetime-local' name='$key' id='input_$key'></div>";
    }
    elseif($object == "photo_empty"){
        return "<div class='form_item upload_photo'><label for='input_$key'>$item:</label><input type='file' name='$key' id='input_$key'></div>";
    }
    elseif ($object=="coordinates_empty"){
        return "<div class='form_item $key'><p>$item:</p><div class='input_$key' id='input_$key' onclick=\"GetMap('add','0')\">Выберите координаты фотографии</div></div>";
    }
}
function get_item_full($object, $key, $item, $value){
    if ($object=="text_full"){
        return "<div class='form_item'><label for='input_$key '>$item:</label><input type='text' name='$key'  id='input_$key' value='{$value}'></div>";
    }
    elseif ($object=="phone_full"){
        return "<div class='form_item'><label for='input_$key'> $item:</label><input type='text' name='$key' id='input_$key' pattern='^\+375(17|29|33|44)[0-9]{7}$' value='{$value}'></div>";
    }
    elseif ($object == "date_full"){
        $value = date("Y-m-d\TH:i", strtotime($value));
        return "<div class='form_item'><label for='input_$key'>$item:</label><input type='datetime-local' name='$key' id='input_$key' value='{$value}'></div>";
    }
    elseif($object == "photo_full"){
        return "<div class='form_item upload_photo' onmouseover=\"ViewImage('view')\" onmouseout=\"ViewImage('hidden')\"><label for='input_$key'>$item:</label>
                <input type='text' name='$key' id='input_$key' value='{$value}' disabled></div><div class='preview'><img id='upload_photo_view' src='../{$value}'/></div>";
    }
    elseif ($object=="coordinates_full"){
        return "<div class='form_item $key'><label for='input_$key'>$item:</label><input type='text' name='$key' id='input_$key' value='{$value}' onclick=\"GetMap('edit', '$value')\"></div>";
    }
    elseif ($object=="type_user_full"){
        if ($value=="Company"){
            return "<div class='form_item $key'><label for='input_$key'>$item:</label><select name='$key' id='input_$key'><option selected>Company</option><option>User</option></select></div>";
        }
        else{
            return "<div class='form_item $key'><label for='input_$key'>$item:</label><select name='$key' id='input_$key'><option>Company</option><option selected>User</option></select></div>";
        }
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
