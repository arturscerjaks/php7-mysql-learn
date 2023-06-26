<?php

/**This is an interface for the EntryPoint class constructor*/

namespace Framework;

interface Website {
    public function getDefaultRoute(): string;
    public function getController(string $controllerName): ?object;
}