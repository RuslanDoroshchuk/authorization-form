<?php
namespace testAuthForm;

session_start();

require_once 'Autoloader.php';

$email  = mysql_real_escape_string(filter_input(INPUT_POST, 'email'));
$pass   = mysql_real_escape_string(filter_input(INPUT_POST, 'pass'));
$logout = mysql_real_escape_string(filter_input(INPUT_POST, 'logout'));

if ($email && $pass){
    \testAuthForm\classes\Authorization::checkUser($email, $pass);
} elseif ($logout) {
    \testAuthForm\classes\Authorization::logout();
} else {
    echo "Empty email or password";
}
