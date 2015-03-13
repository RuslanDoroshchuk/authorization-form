<?php
namespace testAuthForm;

require_once 'config.php';

/**
 * Custom class autoloader
 */

class MyAutoload
{
    public static function autoload($classname){
        $classname = ltrim($classname, '\\');
        $classname = ltrim($classname, __NAMESPACE__);
        $classname = ltrim($classname, '\\');
        $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
        $filename = $classname .".php";
        include_once($filename);
        
    }
}

spl_autoload_register(array('testAuthForm\MyAutoload', 'autoload'));