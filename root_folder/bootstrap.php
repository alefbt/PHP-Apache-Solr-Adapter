<?php
/**
 * @author Yehuda Daniel Korotkin
 */

 // Auto loader
function autoload($classname) {
    $classname = str_replace('_','/', strtolower($classname));   
    
    $filename = dirname(__FILE__). "/classes/". $classname .".php";
    if(file_exists($filename))
        include_once($filename);
    
}
spl_autoload_register('autoload');
?>
