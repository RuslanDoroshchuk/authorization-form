// get info about authorization status and display form
function authFormUpdate(){
    data = {};
    data.info = true;
    
    // delete all error markers
    $('.auth_error').each(function(){
        $(this).removeClass('auth_error');
    });
    
    $.ajax({
        type: "post",
        dataType: "json",
        data: data,
        url: "auth.php",
        success: function (rez) {
            window.authState = rez;
            if (rez.userId){
                $('#logoutForm').show();
                $('#authform').hide();
                $('#captcha').hide();
            } else {
                $('#logoutForm').hide();
                $('#authform').show();
                if (rez.captcha){
                    $('#captcha').show();
                    $('#img_captcha').attr('src',rez.captha_img.replace('&amp;','&'));
                } else {
                    $('#captcha').hide();
                }
            }
            $('#msg').html(rez.msg);
            return false;
        }
    });
}

$(function(){
    
    authFormUpdate();
    
    // кнопка Увійти
    $('#btn_login').click(function(){
        // delete all error markers
        $('.auth_error').each(function(){
            $(this).removeClass('auth_error');
        });

        var data = {};      // data for sending at ajax
        data.email = $('#login').val().trim();
        data.pass = $('#pass').val().trim();
        data.code = $('#code_captcha').val().trim();
        
        // check is captha code enter
        var is_captcha_ok = true;
        if (window.authState.captcha){
            is_captcha_ok = data.code ? true : false;
        }
        
        if (data.email && data.pass && is_captcha_ok){
            $.ajax({
                type: "post",
                dataType: "json",
                data: data,
                url: "auth.php",
                success: function (rez) {
                    if (rez.answer == 'NO'){
                        alert(rez.msg);
                    }
                    authFormUpdate();
                    return false;
                }
            });
        } else {
            // select empty fields
            $('#login, #pass, #code_captcha').each(function(){
                if (!$(this).val().trim()){
                    $(this).addClass('auth_error');
                }
            });
            
            alert('Please, fill all fields');
        }
    });

    // кнопка Вийти
    $('#btn_exit').click(function(){
        var data = {};
        data.logout = "yes";
        
        $.ajax({
            type: "post",
            data: data,
            url: "auth.php",
            success: function (html) {
                authFormUpdate();
                return false;
            }
        });
        
        return false;
        
    });

});

