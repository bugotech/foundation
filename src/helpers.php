<?php

use Illuminate\Container\Container;

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string  $make
     * @return mixed|\Bugotech\Foundation\Application
     */
    function app($make = null)
    {
        if (is_null($make)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($make);
    }
}

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param  string|null  $key
     * @param  mixed|null $default
     * @return mixed|\Bugotech\Foundation\Env
     */
    function env($key = null, $default = null)
    {
        if (is_null($key)) {
            return new \Bugotech\Foundation\Env();
        }

        return \Bugotech\Foundation\Env::get($key, $default);
    }
}

if (! function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = '')
    {
        return app()->basePath().($path ? '/'.$path : $path);
    }
}

if (! function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (! function_exists('logger')) {
    /**
     * Set new log or return Logger.
     * @param null|string $error
     * @return \Monolog\Logger|bool
     */
    function logger($error = null)
    {
        $log = app('log');

        if (is_null($log)) {
            return $log;
        }

        return $log->error($error);
    }
}