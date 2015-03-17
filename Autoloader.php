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
        if (file_exists($filename)){
            include_once($filename);
        }
    }
    
    public static function autoload_from_classes($classname){
        $path = explode('\\', $classname);
        $classShort = array_pop($path);
        $filename = 'classes'.DIRECTORY_SEPARATOR.$classShort.".php";
        if (file_exists($filename)){
            include_once($filename);
        }
    }
}

spl_autoload_register(array('testAuthForm\MyAutoload', 'autoload'));
spl_autoload_register(array('testAuthForm\MyAutoload', 'autoload_from_classes'));