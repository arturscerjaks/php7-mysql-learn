<?php

use App\Classes\EntryPoint;

include_once __DIR__ . '/../Classes/EntryPoint.php';

$uri = strtok(ltrim($_SERVER['REQUEST_URI'], '/'), '?');

$entryPoint = new EntryPoint();
$entryPoint->run($uri);


