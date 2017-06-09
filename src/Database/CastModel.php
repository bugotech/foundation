<?php namespace Bugotech\Foundation\Database;

trait CastModel
{
    protected static $alias = [
        'str' => 'string',
        'num' => 'float',
        'bol' => 'bool',
        'dat' => 'datetime',
        'txt' => 'string',
        'lst' => 'string',
        'lkp' => 'int',
    ];

    /**
     * Traduzir casts do builder.
     * @param $key
     * @return string
     */
    protected function getCastType($key)
    {
        $cast = parent::getCastType($key);

        // Traduzir campos do builder
        return array_key_exists($cast, self::$alias) ? self::$alias[$cast] : $cast;
    }
}