$(function(){
    // кнопка Увійти
    $('#btn_login').click(function(){
        var data = {};
        data.email = $('#login').val().trim();
        data.pass = $('#pass').val().trim();
        
        if (data.email && data.pass){
            $.ajax({
                type: "post",
                data: data,
                url: "auth.php",
                success: function (html) {
                    var ans = html.trim().substring(0,5);
                    if (ans == 'Hello'){
                        $('#authform').hide();
                        $('#captcha').hide();
                        $('#loginedForm').show();
                        $('#msg').html(html);
                    } else {
                        if (ans == 'CAPTC') {
                            $('#captcha').show();
                            alert('Incorrect password! Please, fill all fields and captcha!');
                        } else {
                            if (html.trim()){
                                alert(html);
                            }
                        }
                    }
                    return false;
                }
            });
        } else {
            alert('Please, enter email and password');
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
                return false;
            }
        });

        $('#authform').show();
        $('#loginedForm').hide();
        $('#captcha').hide();
        $('#msg').html('');

    });

});

