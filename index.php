<?php
session_start();

require_once 'config.php';

function __autoload($classname) {
    $filename = "classes/". $classname .".php";
    include_once($filename);
}

$auth = new Authorization();

require_once 'templates/index.php';