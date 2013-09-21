<?php
/**
 * @codeCoverageIgnore
 */
class Loader {

    public static function registerAutoload($engine = 'baseloaderClass') {
        return spl_autoload_register(array(__CLASS__, $engine));
    }

    public static function unregisterAutoload($engine = 'baseloaderClass') {
        return spl_autoload_unregister(array(__CLASS__, $engine));
    }

    public static function baseloaderClass($class) {
        $file = __DIR__ . '/../' . strtr($class, '\\', '//') . '.php.inc';

        if (file_exists($file)) {
            include($file);
		}

    }
}

Loader::registerAutoload("baseloaderClass");