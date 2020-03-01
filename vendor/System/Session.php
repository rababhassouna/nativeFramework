<?php

declare(strict_types=1);

namespace System;

class Session
{
    /**
     * @var Application $app
     */
    private $app;

    /**
     * Session constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * start session if not set session id
     */
    public function start(): void
    {
        ini_set('session.use_only_cookies', "1");

        if (!session_id()) {
            session_start();
        }
    }

    /**
     * @param string $key
     * @param $value string|integer
     * @return Session
     */
    public function set(string $key, $value): session
    {
        return $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return Session
     */
    public function get(string $key): session
    {
        return $_SESSION[$key];
    }

    /**
     * @param string $key
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * destroy all sessions
     */
    public function destroy(): void
    {
        session_destroy();
        unset($_SESSION);
    }
}
