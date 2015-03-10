<?php
session_start();

if ($_POST['email'] && $_POST['pass']){
	$con = mysql_connect('localhost','test',"test")or die(mysql_error);
	mysql_select_db('test');
	mysql_query('SET CHARACTER SET utf8',$con);
	
	$email = $_POST['email'];
	$pass = $_POST['pass'];
	$q = mysql_query("SELECT id, last_visit FROM users WHERE email = '$email' AND password = md5('$pass')");
	
	// if login and pass correct
	if ($q && mysql_num_rows($q)){
		$user = mysql_fetch_assoc($q);
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['last_visit'] = date('d/m/Y H:i', strtotime($user['last_visit']));
		$_SESSION['wrong_pass'] = 0;
		// update last login
		$q2 = mysql_query('UPDATE users SET last_visit = "'.date('Y-d-m H:i:s').'"');
		echo "Hello! Your id: ".$user['id'].", last visit: ".date('d/m/Y H:i', strtotime($user['last_visit']));
	} else {
		if (isset($_SESSION['wrong_pass']) && ($_SESSION['wrong_pass']>0)){
			if ($_SESSION['wrong_pass']==2){
				echo "CAPTCHA";
			}
			$_SESSION['wrong_pass'] = 2;
		} else {
			$_SESSION['wrong_pass'] = 1;
		}
			
		
		echo "INCORRECT LOGIN or PASSWORD";
	}
} elseif($_POST['logout']) {
	$_SESSION['user_id'] = 0;
	//echo 'OK';

} else {
	echo "Empty email or password";
}

?>