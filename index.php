<?php
namespace testAuthForm;

session_start();

require_once 'Autoloader.php';

$auth = new \testAuthForm\classes\Authorization();

require_once 'templates/index.php';
