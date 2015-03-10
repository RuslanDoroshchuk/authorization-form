<?php
session_start();

$con = mysql_connect('localhost','test',"test")or die(mysql_error);
mysql_select_db('test');
mysql_query('SET CHARACTER SET utf8',$con);

/************ 1 ****************/
//перевірка на існування таблиці
$q = mysql_query("
					SELECT * 
					FROM information_schema.tables
					WHERE table_schema = 'test' 
						AND table_name = 'users'
					LIMIT 1;
					");

if ($q && mysql_num_rows($q)){
	//echo 'table exist';
} else {
	$q2 = mysql_query("CREATE TABLE users (
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			email VARCHAR(50) NOT NULL,
			password VARCHAR(255) NOT NULL,
			last_visit TIMESTAMP
			)");
	/***** 2 додати тестового користувача *********/
	$q3 = mysql_query("INSERT INTO users (email, password, last_visit) VALUES ('mail@mail.ua', md5('1111'), '0000-00-00 00:00:00')");
	//echo "table not exist. Table created";
}

?>

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

<script>
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
					url: "http://<?=$_SERVER['SERVER_NAME']?>/auth.php",
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
				url: "http://<?=$_SERVER['SERVER_NAME']?>/auth.php",
				success: function (html) {
					alert(html);
					return false;
				}
			});
			
			$('#authform').show();
			$('#loginedForm').hide();
			$('#captcha').hide();
			$('#msg').html('');
			
		});
		
	});
</script>
</head>

<body>
	
	<div id='authform'>
		<label for='login'>E-mail</label><input name='login' id='login' /><br/>
		<label for='pass'>Password</label><input name='pass' id='pass' /><br/>
		<input type='submit' value='login' id='btn_login' />
	</div>
	
	<div id='loginedForm'>
		<div id='msg'><?php echo ($_SESSION['user_id']) ? "Hello! Your id: ".$_SESSION['user_id'].", last visit: ".$_SESSION['last_visit'] : ""; ?></div>
		<button id='btn_exit'>LogOut</button>
	</div>
	
	<div id='captcha'>
		CAPTCHA
	</div>
</body>

</html>

