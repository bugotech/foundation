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
     * @return mixed|string|\Bugotech\Foundation\Env
     */
    function env($key = null, $default = null)
    {
        if (is_null($key)) {
            return new \Bugotech\Foundation\Env();
        }

        return \Bugotech\Foundation\Env::get($key, $default);
    }
}

if (! function_exists('event')) {
    /**
     * Fire an event and call the listeners or return Dispatcher.
     *
     * @param  object|string  $event
     * @param  mixed   $payload
     * @param  bool    $halt
     * @return array|null|\Illuminate\Contracts\Events\Dispatcher
     */
    function event($event = null, $payload = [], $halt = false)
    {
        if (is_null($event)) {
            return app('events');
        }

        return app('events')->fire($event, $payload, $halt);
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
        // Verificar se esta sendo executado em um PHAR.
        if (app()->runningInPhar() && function_exists('bin_path')) {
            return bin_path($path);
        }

        return app()->basePath() . ($path ? '/' . $path : $path);
    }
}

if (! function_exists('storage_path')) {
    /**
     * Get the path to the storage of the install.
     *
     * @param  string  $path
     * @return string
     */
    function storage_path($path = '')
    {
        return app()->path('storage', $path);
    }
}

if (! function_exists('app_path')) {
    /**
     * Get the path to the application of the install.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = '')
    {
        return app()->path('app', $path);
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

        if (is_null($error)) {
            return $log;
        }

        return $log->error($error);
    }
}

if (! function_exists('cache')) {
    /**
     * Return cache manager or store.
     *
     * @param null|string $driver
     * @return \Illuminate\Cache\CacheManager|\Illuminate\Contracts\Cache\Store
     */
    function cache($driver = null)
    {
        $cache = app('cache');

        if (is_null($cache)) {
            return $cache;
        }

        return $cache->driver($driver);
    }
}

if (! function_exists('validator')) {
    /**
     * Estrutura de validação.
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return \Illuminate\Validation\Factory|\Illuminate\Validation\Validator
     */
    function validator($data = null, array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $validator = app('validator');

        if (is_null($data)) {
            return $validator;
        }

        return $validator->make($data, $rules, $messages, $customAttributes);
    }
}

if (! function_exists('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param  string  $value
     * @return string
     */
    function encrypt($value)
    {
        return app('encrypter')->encrypt($value);
    }
}

if (! function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param  string  $value
     * @return string
     */
    function decrypt($value)
    {
        return app('encrypter')->decrypt($value);
    }
}

if (! function_exists('hasher')) {
    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string|\Illuminate\Contracts\Hashing\Hasher
     *
     * @throws \RuntimeException
     */
    function hasher($value = null, array $options = [])
    {
        if (! is_null($value)) {
            return app('hash');
        }

        return app('hash')->make($value, $options);
    }
}