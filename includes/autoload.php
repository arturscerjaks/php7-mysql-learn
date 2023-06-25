<?php

/**Automatically loads classes as they are called*/

function autoloader($className)
{
    $fileName = str_replace('\\', '/', $className) . '.php';

    $file = __DIR__ . '/../' . $fileName;

    include $file;
}

spl_autoload_register('autoloader');
