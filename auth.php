<?php
namespace testAuthForm;

session_start();

require_once 'Autoloader.php';

$email  = filter_input(INPUT_POST, 'email');
$pass   = filter_input(INPUT_POST, 'pass');
$code   = filter_input(INPUT_POST, 'code');
$logout = filter_input(INPUT_POST, 'logout');
$info   = filter_input(INPUT_POST, 'info');

if ($email && $pass){
    classes\Authorization::checkUser($email, $pass, $code);
} elseif ($logout) {
    classes\Authorization::logout();
} elseif ($info) {
    classes\Authorization::getAuthInfo();
} else {
    echo "Empty email or password";
}
