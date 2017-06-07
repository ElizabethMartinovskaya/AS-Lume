var list = {};
$(document).ready(function() {
    $('.nav_menu ul').on('click', 'li', function () {
        $('.list_info').css('overflow-x','hidden');
        var block_info = $('.block_info');
        $(block_info).hide();
        $(block_info).slideDown(800);
        $('.nav_menu ul li').each(function (key, item) {
            $(item).removeClass('active');
        });
        $(this).addClass('active');
        var current_li = $(this).children('a').children('h1').text();
        $('title').html(current_li);
        $('.header_info h1').text(current_li);
        $('.choose_menu').html('');
        $('.list_info').html('');
        $('.search').html('');
        if ($(this).attr('data-function')=='add') {
            $.post('../php/generation_item.php', "get_add_form="+current_li, function (data) {
                CreateAddForm(data, current_li);
                Validate('add_item_form');
            });
        }
        else {
            $('.list_info').css('flex-direction','column');
            $.post('../php/functions.php', "get_search_form="+current_li, function (data) {
                $('.search').html(data);
                var search_input = $(".search").find("input[type='search']");
                var search_field = $("#search_select");
                $(".search").on("Search", function(e) {
                    Search(search_input, search_field,current_li);
                });
                $(search_input).keyup(function () {
                    Search(search_input, search_field,current_li);
                });
                $(search_field).change(function () {
                    Search(search_input,search_field,current_li);
                });
            });
            $.post('../php/functions.php', "get_checkbox_list="+current_li, function (data) {
                $('.choose_menu').html(data);
            });
            $.post('../php/functions.php', "current_li="+current_li+"&&form=user_setting", function (data) {
                $('.list_info').html(data);
                if (current_li == 'Настройки'){
                    Validate('user_setting');
                    $('.search').html('');
                    $('.block_info').on('submit','#user_setting',function (sub) {
                        sub.preventDefault();
                        $.post('../php/update_functions.php', "form=user_setting&&"+$(this).serialize(), function(data){
                            $.post('../php/generation_item.php', "dialog_text="+data+"&&type=message", function(dialog_window) {
                                get_message(dialog_window);
                                if(data=="Изменения сохранены"){
                                    $('#user_setting')[0].reset();
                                }
                            });
                        })
                    });
                }
            });
        }
    });
    $('.nav_menu ul li:first-child').click();
});

function CreateAddForm(input_result, current_li){
    if (current_li=="Фотография"){
        $('.list_info').html("<form enctype='multipart/form-data' id='add_item_form' action='../php/upload_photos.php' method='post'></form>");
    }
    else{
        $('.list_info').html("<form id='add_item_form'></form>");
    }
    $('.list_info form#add_item_form').html(input_result);
    $('.list_info form#add_item_form').append("<div class='form_item'><input type='submit' name='add_item' value='Добавить'></div>");
    if (current_li=="Фотография"){
        $('.list_info').append("<div id='map_canvas_add' class='map'></div>");
        $('.list_info').css('flex-direction','row');
    }
    $("#add_item_form").unbind('submit');
    var file;
    $('input[type=file]').change(function(){
        file =  this.files[0];
    });
    $('#add_item_form').submit(function (sub) {
        sub.preventDefault();
        if (current_li=="Фотография"){
            var form = $(this).serializeArray();
            var formData = new FormData();
            formData.append('Image_src', file);
            for (var i=0; i < form.length; i++){
                formData.append(form[i].name, form[i].value);
            }
            formData.append('image_N',marker.getPosition().lat());
            formData.append('image_E',marker.getPosition().lng());
            $.ajax({
                url: '../php/upload_photos.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(result_update) {
                    $.post('../php/generation_item.php', "dialog_text="+result_update+"&&type=message", function(dialog_window) {
                        get_message(dialog_window);
                        if(result_update=="Фотография добавлена"){
                            $('#add_item_form')[0].reset();
                            $('#map_canvas_add').html('');
                        }
                    });
                }
            });
        }
        else{
            $.post('../php/update_functions.php', $(this).serialize()+"&&form=add_item_form"+"&&current_li="+current_li, function(result_update){
                $.post('../php/generation_item.php', "dialog_text="+result_update+"&&type=message", function(dialog_window) {
                    get_message(dialog_window);
                    if(result_update=="Пользователь добавлен" || result_update=="Компания добавлена"){
                        $('#add_item_form')[0].reset();
                    }
                });
            })
        }
    })
}
$count_checked = 0;
function DataQuery(current_checkbox){
    current_checkbox = $(current_checkbox).prev();
    var this_checked = true;
    $('.choose_menu').children('div').children('input:checkbox:checked').each(function(key, item){
        if ($(item).attr('value') == $(current_checkbox).attr('value')) {
            this_checked = false;
        }
    });
    if (this_checked) {
        $count_checked++;
        $('.list_cell.'+$(current_checkbox).attr('value')).css('display','table-cell');
        $('.list_table_header_item.'+$(current_checkbox).attr('value')).css('display','table-cell');
    }
    else {
        $('.list_cell.'+$(current_checkbox).attr('value')).css('display','none');
        $('.list_table_header_item.'+$(current_checkbox).attr('value')).css('display','none');
        $count_checked--;
    }
    if($('.list_table').width() >= $('.list_info').width()){
        $('.list_info').css('overflow-x','scroll');
    }
    else{
        $('.list_info').css('overflow-x','hidden');
    }
    if ($count_checked>0){
        $('.list_table_header_item.options').css('display','table-cell');
        $('.list_cell.options').css('display','flex');
    }
    else{
        $('.list_table_header_item.options').css('display','none');
        $('.list_cell.options').css('display','none');
    }
}

function ReturnIdForOperation(type_operation, current_li, item_name, item_id){
    if(type_operation=='edit'){
        list['Пользователи'] = {
            'users_name': 'Имя',
            'users_password': 'Пароль',
            'users_email': 'e-mail',
            'phone_number': 'Номер телефона',
            'country_name':'Страна',
            'city_name':'Город'
        };
        list['Компании'] = {
            'users_name':'Название',
            'users_password':'Пароль',
            'users_email':'e-mail',
            'phone_number':'Номер телефона',
            'country_name': 'Страна',
            'city_name': 'Город'
        };
        list['Фотографии'] = {
            'Image_src':'Фото',
            'users_name':'Автор',
            'type_user':'Тип автора',
            'description_image':'Описание',
            'Source':'Ресурс',
            'type_data':'Тип ресурса',
            'publication_date':'Дата публикации',
            'NE':'Координаты'
        };
        $.post('../php/generation_item.php', 'create_edit_form='+current_li+'&&item_id='+item_id+"&&edit_list="+ JSON.stringify(list), function(data){
            $('.window').html(data);
            $('#click_edit')[0].click();
            Validate('edit_form');
            $('#edit_form').submit(function (sub) {
                sub.preventDefault();
                var form=$(this).serialize();
                $.post('../php/update_functions.php',form+'&&operation=edit_item'+'&&item_id='+item_id+'&&current_li='+current_li, function (data) {
                    $.post('../php/generation_item.php', "dialog_text="+data+"&&type=message", function(dialog_window) {
                        get_message(dialog_window);
                    });
                });
            })
        })
    }
    if (type_operation=='delete') {
        $.post('../php/generation_item.php', "dialog_text=Вы действительно хотите удалить пользователя "+item_name+"?"+"&&type=dialog", function(dialog_window) {
            get_dialog('delete',current_li, item_id, dialog_window);
        });
    }
    if (type_operation=='confirm'){
        $.post('../php/generation_item.php', "dialog_text=Вы действительно хотите подтвердить фотографию "+item_name+"?"+"&&type=dialog", function(dialog_window) {
            get_dialog('confirm',current_li, item_id, dialog_window);
        });
    }
}

function Validate(id) {
    var required = "Поле обязательно для заполнения";
    var min_length_2 = "Минимальная длина 2 символа";
    var min_length_4 = "Минимальная длина 4 символа";
    var max_length_64 = "Максимальная длина 64 символа";
    var length_13 = "Длина телефона 13 символов. Формат +375291750482 ";
    var user_name_rules={
        required: true,
        minlength: 2,
        maxlength: 64
    };
    var user_log_pass_rules={
        required: true,
        minlength: 4,
        maxlength: 64
    };
    var user_email_rules={
        required: true,
        email: true
    };
    var source_rules={
        required: true,
        minlength: 4
    };
    var type_data_rules={
        required: true,
        minlength: 2
    };
    var user_name_message={
        required: required,
        minlength: min_length_2,
        maxlength: max_length_64
    };
    var user_log_pass_message={
        required: required,
        minlength: min_length_4,
        maxlength: max_length_64
    };
    var user_email_message={
        required: required,
        email: "Введите валидный e-mail адрес"
    };
    var source_message={
        required: required,
        minlength: min_length_4
    };
    var type_data_message={
        required: required,
        minlength: min_length_2
    }
        $('#'+id+'').validate({
        rules: {
            user_login: user_log_pass_rules,
            user_old_password: user_log_pass_rules,
            user_new_password: user_log_pass_rules,
            user_replay_new_password: {
                required: true,
                equalTo: '#user_new_password',
                minlength: 4,
                maxlength: 64
            },
            users_name: user_name_rules,
            users_password: user_log_pass_rules,
            users_email: user_email_rules,
            phone_number: {
                required: true,
                minlength: 13,
                maxlength: 13
            },
            country_name: {required: true},
            city_name: {required: true},
            Image_src: {required: true},
            Source: source_rules,
            type_data: type_data_rules
        },
        messages: {
            user_login: user_log_pass_message,
            user_old_password:  user_log_pass_message,
            user_new_password: user_log_pass_message,
            user_replay_new_password: {
                required: required,
                equalTo: "Вы неверно повторили пароль",
                minlength: min_length_4,
                maxlength: max_length_64
            },
            users_name: user_name_message,
            users_password: user_log_pass_message,
            users_email: user_email_message,
            phone_number: {
                required: required,
                minlength: length_13,
                maxlength: length_13
            },
            country_name: {required: required},
            city_name: {required: required},
            Image_src: {required: required},
            Source: source_message,
            type_data: type_data_message
        }
    });
};

function all_checkboxes(check_all) {
    $(".choose_menu").find("input:checkbox:checked").each(function (key, item) {
      $(item).parent().find("label").click()
    });
    if(check_all){
        $(".choose_menu").find("label").each(function (key,item) {
            item.click();
        });
    }
}

function Search (search_input, search_field, current_li) {
    if(search_input.val().length != 0) {
        $.post('../php/functions.php', "search_items=" + encodeURIComponent(search_input.val())+"&search_field="+search_field.val()+"&current_li_="+current_li, function (data) {
            $(".list_info").html(data);
            all_checkboxes(true);
        })
    }
    else {
        $.ajax({
            type: "POST",
            url: "../php/functions.php",
            data: "&&current_li="+current_li+"&&form=user_setting",
            success: function (data) {
                $('.list_info').html(data);
                if (current_li == 'Настройки'){
                    Validate('user_setting');
                }
            }
        });
        all_checkboxes(false);
    }
}
var map;
var myLatlng;
var mapOptions;
var marker;
var status;
function GetMap(type, coordinates){
    if (type=="add"){
        myLatlng = new google.maps.LatLng(53.90301904723439, 27.55883505550067);
        mapOptions = {
            zoom: 15,
            center: myLatlng
        };
        map = new google.maps.Map(document.getElementById("map_canvas_add"), mapOptions);
    }
    else if (type=="edit") {
        coordinates=coordinates.split(' ');
        myLatlng = new google.maps.LatLng(coordinates[0], coordinates[1]);
        mapOptions = {
            zoom: 15,
            center: myLatlng
        };
        $('.popup.edit_image').css('width','1000px');
        map = new google.maps.Map(document.getElementById("map_canvas_edit"), mapOptions);
        status=1;
    }
    else{
        coordinates=coordinates.split(' ');
        myLatlng = new google.maps.LatLng(coordinates[0], coordinates[1]);
        mapOptions = {
            zoom: 15,
            center: myLatlng
        };
        map = new google.maps.Map(document.getElementById("map_canvas_view"), mapOptions);
    }
    marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        draggable: true,
        title: "Перетащи меня!"
    });
    if (status==1){
        google.maps.event.addListener(marker,'position_changed', function() {
            var coordinates = marker.getPosition().lat()+' '+marker.getPosition().lng();
            $.post('../php/get_html.php', "Координаты="+coordinates, function (input) {
                $('.form_item.NE').html(input);
            });
        });
    }
}

function ViewImage(type){
    if (type=="view"){
        $('.preview').css('display','flex');
    }
    else {
        $('.preview').css('display','none');
    }
}

function get_message(dialog_window){
    $('.block_info .window').html(dialog_window);
    $('#dialog').dialog({
        modal: true,
        autoOpen: true,
        draggable: false,
        show: {
            effect: "blind",
            duration: 500
        },
        hide: {
            effect: "fade",
            duration: 500
        }
    });
}

function get_dialog(type_of_operation,current_li,item_id,dialog_window){
    $('.block_info .window').html(dialog_window);
    $('#dialog_window').dialog({
        modal: true,
        autoOpen: true,
        draggable: false,
        show: {
            effect: "blind",
            duration: 500
        },
        hide: {
            effect: "fade",
            duration: 500
        },
        buttons: {
            OK: function() {
                $(this).dialog('close');
                $.post('../php/update_functions.php', 'operation='+type_of_operation+'&&current_li='+current_li+'&&item_id='+item_id, function(data){
                    $.post('../php/generation_item.php', "dialog_text="+data+"&&type=message", function(dialog_window) {
                        get_message(dialog_window);
                    });
                })
            },
            'Отмена': function() {
                $(this).dialog('close');
            }
        }
    });
}

function ShowHidePassword(id){
    element = $('#'+id)
    element.replaceWith(element.clone().attr('type',(element.attr('type') == 'password') ? 'text' : 'password'))
}