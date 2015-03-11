<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
        <style>
                <?php if ($_SESSION['user_id']==0) {?>
                #loginedForm{display:none;}
                <?php } else { ?>
                #authform{display:none;}
                <?php } ?>
                #captcha{display:none;}
        </style>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/auth.js"></script>
    </head>
    <body>
	
	<div id='authform'>
            <label for='login'>E-mail</label>
            <input name='login' id='login' />
            <br/>
            
            <label for='pass'>Password</label>
            <input name='pass' id='pass' type='password'/>
            <br/>
            
            <input type='submit' value='login' id='btn_login' />
	</div>
	
	<div id='loginedForm'>
            <div id='msg'><?=$auth->writeHello()?></div>
            <button id='btn_exit'>LogOut</button>
	</div>
	
	<div id='captcha'>CAPTCHA</div>
        
    </body>
</html>
