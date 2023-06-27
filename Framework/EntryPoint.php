<?php

/**Front-controller generic framework class*/

namespace Framework;

use Framework\Website;

class EntryPoint
{
    private $website;

    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function run(string $uri, string $method)
    {
        try {
            $this->checkUri($uri);

            if ($uri == '') {
                $uri = $this->website->getDefaultRoute();
            }

            $route = explode('/', $uri);

            $controllerName = array_shift($route);
            $action = array_shift($route);

            if ($method === 'POST') {
                $action .= 'Submit';
            }

            $controller = $this->website->getController($controllerName);

            if (is_callable([$controller, $action])) {
                $page = $controller->$action(...$route);
                
                $title = $page['title'];
                
                $variables = $page['variables'] ?? [];       
                $output = $this->loadTemplate($page['template'], $variables);
            } else {
                http_response_code(404);
                $title = 'Not found';
                $output = 'Sorry, the page you are looking for could not be found';
            }
        } catch (\PDOException $e) {
            $title = 'An error has occurred';

            $output = 'Database error: ' . $e->getMessage() . ' in ' .
                $e->getFile() . ':' . $e->getLine();
        }
        include __DIR__ . '/../templates/layout.html.php';
    }

    private function loadTemplate($templateFileName, $variables)
    {
        extract($variables);

        ob_start();
        include __DIR__ . '/../templates/' . $templateFileName;

        return ob_get_clean();
    }

    private function checkUri($uri)
    {
        if ($uri != strtolower($uri)) {
            http_response_code(301);
            header('location: /' . strtolower($uri));
        }
    }
}
