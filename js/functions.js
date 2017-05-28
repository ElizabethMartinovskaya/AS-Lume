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
        if ($(this).attr('data-function')=='add')
        {
            $.post('../php/generation_item.php', "get_add_form="+current_li, function (data) {
                create_add_form(data, current_li);
                setEvent('add_item_form');
            });
        }
        else {
            $.post('../php/functions.php', "get_search_form="+current_li, function (data) {
                $('.search').html(data);
                var search_input = $(".search").find("input[type='search']");
                var search_field = $("#search_select");
                $(".search").on("search", function(e) {
                    search(search_input, search_field,current_li);
                });
                $(search_input).keyup(function () {
                    search(search_input, search_field,current_li);
                });
                $(search_field).change(function () {
                    search(search_input,search_field,current_li);
                });
            });
            $.post('../php/functions.php', "get_checkbox_list="+current_li, function (data) {
                $('.choose_menu').html(data);
            });
            $.post('../php/functions.php', "current_li="+current_li+"&&form=user_setting", function (data) {
                $('.list_info').html(data);
                if (current_li == 'Настройки'){
                    setEvent('user_setting');
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
        };
    });
    $('.nav_menu ul li:first-child').click();
});
function select_list(current_select,id_form){
    var select_result = "";
    list['models_device'] = {
        'name_model': 'Модель устройства',
        'name_brand': 'Брэнд устройства',
        'screen_size': "Диагональ экрана",
        'width_model_screen': "Высота экрна",
        'height_model_screen': 'Ширина экрана'
    };
    if ($('#models_device option:selected').text()=='Своя модель'){
        $.each(list[current_select], function(key, item){
            select_result+=get_text_input(key,item);
        });
        $('form#'+id_form+' .block_models').append(select_result);
    }
    else{
        $('form#'+id_form+' .block_models').empty();
    }
}

function create_add_form(input_result, current_li){
    if (current_li=="Фотография"){
        $('.list_info').html("<form enctype='multipart/form-data' id='add_item_form' action='../php/upload_photos.php' method='post'></form>");
    }
    else{
        $('.list_info').html("<form id='add_item_form'></form>");
    }
    $('.list_info form#add_item_form').html(input_result);
    $('.list_info form#add_item_form').append("<div class='form_item'><input type='submit' name='add_item' value='Добавить'></div>");
    $("#add_item_form").unbind('submit');
    var file;
    $('input[type=file]').change(function(){
        file = this.files[0];
    });
    $('#add_item_form').submit(function (sub) {
        sub.preventDefault();
        if (current_li=="Фотография"){
            var form = $(this).serializeArray();
            var formData = new FormData();
            formData.append('Image_src', file);
            for (var i=0; i < 4; i++){
                formData.append(form[i].name, form[i].value);
            }
            $.ajax({
                url: '../php/upload_photos.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(result_update) {
                    console.log(result_update);
                    $.post('../php/generation_item.php', "dialog_text="+result_update+"&&type=message", function(dialog_window) {
                        get_message(dialog_window);
                        if(result_update=="Фотография добавлена"){
                            $('#add_item_form')[0].reset();
                        }
                    });
                },
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
function get_text_input(key,item) {
    return "<div class='form_item'><label for='input_" + key + "'>" + item + ":</label><input type='text' name=" + key + " id='input_" + key + "'></div>";
}
$count_checked = 0;
function data_query(current_checkbox){
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
    else
    {
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
function ReturnIdForOperation(type_operation, current_li, name){
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
        $.post('../php/generation_item.php', 'create_edit_form=' +current_li+'&&name='+name+"&&edit_list="+ JSON.stringify(list), function(data){
            $('.window').html(data);
            $('#click_edit')[0].click();
            setEvent('edit_form');
            $('#edit_form').submit(function (sub) {
                sub.preventDefault();
                $.post('../php/update_functions.php',$(this).serialize()+ '&&operation=edit_item'+'&&item_id='+name+'&&current_li='+current_li, function (data) {
                    $.post('../php/generation_item.php', "dialog_text="+data+"&&type=message", function(dialog_window) {
                        get_message(dialog_window);
                    });
                });
            })
        })
    }
    else {
        $.post('../php/generation_item.php', "dialog_text=Вы действительно хотите удалить пользователя "+name+"?"+"&&type=dialog", function(dialog_window) {
            get_dialog(current_li, name, dialog_window);
        });
    }
}

function setEvent(id) {
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
            users_surname: user_name_rules,
            users_login: user_log_pass_rules,
            users_password: user_log_pass_rules,
            users_email: user_email_rules,
            phone_number: {
                required: true,
                minlength: 13,
                maxlength: 13
            },
            country_name: {required: true},
            city_name: {required: true}
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
            users_surname: user_name_message,
            users_login: user_log_pass_message,
            users_password: user_log_pass_message,
            users_email: user_email_message,
            phone_number: {
                required: required,
                minlength: length_13,
                maxlength: length_13
            },
            country_name: {required: required},
            city_name: {required: required}
        }
    });
};

function all_checkboxes(check_all) {
    $(".choose_menu").find("input:checkbox:checked").each(function (key, item)
    {
      $(item).parent().find("label").click()
    });
    if(check_all)
    $(".choose_menu").find("label").each(function (key,item) {
       item.click();
    });
}

function search (search_input, search_field, current_li) {
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
                    setEvent('user_setting');
                }
            }
        });
        all_checkboxes(false);
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

function get_dialog(current_li, id, dialog_window){
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
                $.post('../php/update_functions.php', 'operation=delete'+'&&current_li='+current_li+'&&id='+id, function(data){
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