<?php
namespace testAuthForm;

session_start();

require_once 'Autoloader.php';

$email  = filter_input(INPUT_POST, 'email');
$pass   = filter_input(INPUT_POST, 'pass');
$code   = filter_input(INPUT_POST, 'code');
$logout = filter_input(INPUT_POST, 'logout');
$info   = filter_input(INPUT_POST, 'info');

$user = new Authorization();

if ($email && $pass){
    $user->checkUser($email, $pass, $code);
} elseif ($logout) {
    $user->logout();
} elseif ($info) {
    $user->getAuthInfo();
} else {
    echo "Empty email or password";
}
