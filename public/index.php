<?php

use App\Classes\EntryPoint;
use App\Classes\JokeWebsite;

include_once __DIR__ . '/../Classes/EntryPoint.php';

$uri = strtok(ltrim($_SERVER['REQUEST_URI'], '/'), '?');

$jokeWebsite = new JokeWebsite();

$entryPoint = new EntryPoint($jokeWebsite);
$entryPoint->run($uri);


