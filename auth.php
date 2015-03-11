<?php
session_start();
require_once 'config.php';

function __autoload($classname) {
    $filename = "classes/". $classname .".php";
    include_once($filename);
}

$email  = mysql_real_escape_string(filter_input(INPUT_POST, 'email'));
$pass   = mysql_real_escape_string(filter_input(INPUT_POST, 'pass'));
$logout = mysql_real_escape_string(filter_input(INPUT_POST, 'logout'));

if ($email && $pass){
    Authorization::checkUser($email, $pass);
} elseif ($logout) {
    Authorization::logout();
} else {
    echo "Empty email or password";
}
