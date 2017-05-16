<?php

if (! function_exists('db')) {

    /**
     * Database DB.
     *
     * @param null $connection
     * @return \Illuminate\Database\Connection|\Illuminate\Database\DatabaseManager
     */
    function db($connection = null)
    {
        $db = app('db');

        if (is_null($connection)) {
            return $db;
        }

        return $db->connection($connection);
    }
}