<?php

if (! function_exists('artisan')) {

    /**
     * Artisan - Console.
     * @return \Illuminate\Console\Application
     */
    function artisan()
    {
        return app('artisan');
    }
}