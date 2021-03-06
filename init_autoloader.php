<?php

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}

set_include_path(get_include_path().PATH_SEPARATOR.ROOT_PATH.'vendor');
set_include_path(get_include_path().PATH_SEPARATOR.ROOT_PATH.'application');
spl_autoload_register('autoload');
