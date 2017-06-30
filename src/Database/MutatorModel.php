<?php namespace Bugotech\Foundation\Database;

trait MutatorModel
{
    public $charcases = [];

    /**
     * Tratar valores.
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function resolveMutator($key, $value)
    {
        $value = $this->resolveCharset($key, $value);

        return $value;
    }

    /**
     * Tratar charcase.
     *
     * @param $key
     * @param $value
     * @return string
     */
    private function resolveCharset($key, $value)
    {
        // Verificar se deve tratar charcase
        if (! array_key_exists($key, $this->charcases)) {
            return $value;
        }

        switch ($this->charcases[$key]) {
            case 'lower': return strtolower($value);
            case 'upper': return strtoupper($value);
            case 'password': return hasher($value);
        }

        return $value;
    }
}