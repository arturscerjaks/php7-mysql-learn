<?php

use App\Classes\EntryPoint;
use App\Classes\JokeWebsite;

include_once __DIR__ . '/../Classes/EntryPoint.php';

$uri = strtok(ltrim($_SERVER['REQUEST_URI'], '/'), '?');

$website = new JokeWebsite();

$entryPoint = new EntryPoint($website);
$entryPoint->run($uri);


