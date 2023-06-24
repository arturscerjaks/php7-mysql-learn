<?php

/**Autoloader function for classes*/

namespace App\Includes;

/**@param string $className*/

function autoloader($className) {
$file = __DIR__ . '/../classes/' . $className . '.php';
include $file;
}

spl_autoload_register('autoloader');