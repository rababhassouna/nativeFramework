<?php

declare(strict_types=1);

namespace System;


class Route
{
    /**
     * @var array $routes
     */
    public $routes = [];
    /**
     * @var Application $app
     */
    private $app;

    /**
     * Route constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * @param $url
     * @param $action
     * @param string $method
     * @return array
     */
    public function add($url, $action, $method = 'GET'): array
    {
        $route = [
            'url' => $url,
            'pattern' => $this->generatePattern($url),
            'action' => $this->getAction($action),
            'method' => strtoupper($method)

        ];
        return $this->routes[] = $route;

    }

    /**
     * @param $url
     * @return string
     */
    private function generatePattern($url): string
    {
        $pattern = '#^';
        $pattern .= str_replace([':text', ':id'], ['([a-zA-Z0-9-]+)', '(\d+)'], $url);
        $pattern .= '$#';
        return $pattern;
    }

    /**
     * @param string $action
     * @return string
     */
    private function getAction(string $action): string
    {
        $action = str_replace('/', '\\', $action);

        return strpos($action, '@') !== false ? $action : $action . '@index';
    }

    /**
     * get routes
     * @return array
     */
    public function getRoute(): array
    {
        if (count($this->routes) > 0) {
            foreach ($this->routes as $route) {
                if ($this->isPatternMatching($route['pattern']) && $this->isMethodMatching($route['method'])) {
                    $arguments = $this->getArguments($route['pattern']);
                    list($controller, $method) = explode('@', $route['action']);

                    return [$controller, $method, $arguments];
                }
            }
        }
    }

    /**
     * @param $pattern
     * @return int
     */
    private function isPatternMatching($pattern): int
    {
        return preg_match($pattern, $this->app->request->url());

    }

    /**
     * @param $method
     * @return bool
     */
    private function isMethodMatching($method): bool
    {
        return $method === $this->app->request->method();
    }

    /**
     * @param $pattern
     * @return array
     */
    private function getArguments($pattern): array
    {
        preg_match($pattern, $this->app->request->url(), $matches);
        array_shift($matches);
        return $matches;
    }
}
