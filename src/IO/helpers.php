<?php

if (! function_exists('files')) {

    /**
     * Files
     * @return \Bugotech\IO\Filesystem
     */
    function files()
    {
        return app('files');
    }
}

if (! function_exists('storage')) {

    /**
     * @param null|string $drive
     * @return Illuminate\Filesystem\FilesystemManager|\Illuminate\Contracts\Filesystem\Filesystem
     */
    function storage($drive = null)
    {
        $manager = app('filesystem');

        if (is_null($drive)) {
            return $manager;
        }

        return $manager->drive($drive);
    }
}