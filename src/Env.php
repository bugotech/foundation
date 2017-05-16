<?php namespace Bugotech\Foundation;

use Bugotech\Support\Str;

class Env
{
    /**
     * Load file .env.
     *
     * @param $path
     * @param string $file
     * @return bool
     */
    public function load($path, $file = '.env')
    {
        try {
            $env = new \Dotenv\Dotenv($path, $file);
            $env->load();

            return true;
        } catch (\Dotenv\Exception\InvalidPathException $e) {
            return false;
        }
    }

    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
