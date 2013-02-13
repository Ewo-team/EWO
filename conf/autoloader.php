<?php

namespace conf;

class Loader {

    public static function registerAutoload($engine = 'baseloaderClass') {
        return spl_autoload_register(array(__CLASS__, $engine));
    }

    public static function unregisterAutoload($engine = 'baseloaderClass') {
        return spl_autoload_unregister(array(__CLASS__, $engine));
    }

    public static function baseloaderClass($class) {
        $file = SERVER_ROOT . '/' . strtr($class, '\\', '//') . '.php';
        if (file_exists($file)) {
            include($file);
		}

    }
    
    public static function eventautoload($name){
		if(file_exists(SERVER_ROOT."/persos/eventManager/formatter/$name.php")){
			include(SERVER_ROOT."/persos/eventManager/formatter/$name.php");
		}
    }
}

Loader::registerAutoload("baseloaderClass");
Loader::registerAutoload("eventautoload");
?>
