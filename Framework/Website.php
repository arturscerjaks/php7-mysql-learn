<?php

/**This is an interface for the EntryPoint class constructor*/

namespace Framework;

interface Website {
    public function getDefaultRoute();
    public function getController(string $controllerName);
}