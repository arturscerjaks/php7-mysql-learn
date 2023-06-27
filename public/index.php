<?php

use Framework\EntryPoint;
use Ijdb\IjdbRoutes;

include __DIR__ . '/../includes/autoload.php';

$uri = strtok(ltrim($_SERVER['REQUEST_URI'], '/'), '?');

$jokeWebsite = new IjdbRoutes();

$entryPoint = new EntryPoint($jokeWebsite);
$entryPoint->run($uri, $_SERVER['REQUEST_METHOD']);


