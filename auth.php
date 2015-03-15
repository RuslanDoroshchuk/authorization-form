<?php
namespace testAuthForm;

session_start();

require_once 'Autoloader.php';

$email  = mysql_real_escape_string(filter_input(INPUT_POST, 'email'));
$pass   = mysql_real_escape_string(filter_input(INPUT_POST, 'pass'));
$code   = mysql_real_escape_string(filter_input(INPUT_POST, 'code'));
$logout = mysql_real_escape_string(filter_input(INPUT_POST, 'logout'));
$info   = mysql_real_escape_string(filter_input(INPUT_POST, 'info'));

if ($email && $pass){
    \testAuthForm\classes\Authorization::checkUser($email, $pass, $code);
} elseif ($logout) {
    \testAuthForm\classes\Authorization::logout();
} elseif ($info) {
    \testAuthForm\classes\Authorization::getAuthInfo();
} else {
    echo "Empty email or password";
}
