<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
        <link rel="stylesheet" type="text/css" href="css/authform.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/auth.js"></script>
    </head>
    <body>
	
        <div class='auth_form'>
            
            <div id='msg'></div>

            <div id='authform'>
                <label for='login'>E-mail</label>
                <input name='login' id='login' />
                <br/>

                <label for='pass'>Password</label>
                <input name='pass' id='pass' type='password'/>
                <br/>

                <div id='captcha'>
                    <label for='code_captcha'>CAPTCHA</label>
                    <input name='code_captcha' id='code_captcha' />
                    <img id='img_captcha' src='' alt='captcha img' />
                </div>

                <input type='submit' value='login' id='btn_login' />
            </div>

            <div id='logoutForm'>
                <button id='btn_exit'>LogOut</button>
            </div>
        
        </div>
        
    </body>
</html>
