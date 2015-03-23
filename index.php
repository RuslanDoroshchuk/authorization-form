<?php
/**
* Authorization form
* 
* Simple authorization form
* Test task for Zinit solutions
* @author Ruslan Doroshchuk
* @version 1.0
*/

namespace testAuthForm;

session_start();

/**
* include autoloader
*/
require_once 'Autoloader.php';

$auth = new Authorization();

/**
* include html template
*/
require_once 'templates/index.php';
