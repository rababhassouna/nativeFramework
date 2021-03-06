<?php

declare(strict_types=1);

namespace System;

class Application
{
    /**
     * @var instance
     */
    private static $instance;
    /**
     * @var container array
     */
    public $container = [];

    /**
     * Application constructor.
     * @param File $file
     */
    private function __construct(File $file)
    {
        $this->share('file', $file);
        $this->registerClasses();
    }

    /**
     * @param $key
     * @param $value
     * add to container
     * @return void
     */
    public function share($key, $value): void
    {
        $this->container[$key] = $value;
    }

    /**
     * register classes in spl load
     */
    private function registerClasses(): void
    {
        spl_autoload_register([$this, 'load']);
    }

    /**
     * @param $file
     * @return Application
     */
    public static function getInstance($file=null): Application
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($file);
        }
        return static::$instance;
    }

    /**
     * load class
     * @param string $class
     */
    public function load(string $class): void
    {
        if (strpos($class, 'App') === 0) {
            $file = $class . '.php';
        } else {
            $file = 'vendor/' . $class . '.php';
        }

        if ($this->file->exists($file)) {
            $this->file->call($file);
        }
    }

    /**
     * @param $name
     * @return null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * get from container
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if (!$this->isSharing($key)) {
            if ($this->isProviders($key)) {
                $this->share($key, $this->createNewObject($key));
            } else {
                die($key . ' not found in container');
            }
        }
        return $this->container[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    private function isSharing(string $key): bool
    {
        return isset($this->container[$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    private function isProviders(string $key): bool
    {
        return isset($this->providers()[$key]);
    }

    /**
     * contain all providers classes
     * @return array
     */
    private function providers(): array
    {
        return
            [
                'request' => 'System\\Http\\Request',
                'response' => 'System\\Http\\Response',
                'session' => 'System\\Session',
                'route' => 'System\\Route',
            ];
    }

    /**
     * @param string $key
     * @return object
     */
    private function createNewObject(string $key): object
    {
        $obj = $this->providers()[$key];
        return new $obj($this);
    }

    /**
     * run application
     */
    public function run(): void
    {
        $this->session->start();
        $this->request->prepareUrl();
        $this->request->url();
        $this->file->call('App/routes.php');
        $this->route->getRoute();
    }
}
