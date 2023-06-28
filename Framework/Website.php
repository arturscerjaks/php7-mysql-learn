<?php

/**This is an interface for the EntryPoint class constructor. Assumes MVC pattern*/

namespace Framework;

interface Website {
    public function getDefaultRoute(): string;
    public function getController(string $controllerName): ?object;
    public function checkLogin(string $uri): ?string;
    public function getLayoutVariables(): array;
}