<?php namespace Bugotech\Foundation\Database;

trait DefaultValuesModel
{
    /**
     * Lista de attributos com valor padrão.
     * @var array
     */
    public $defaultValues = [];

    /**
     * Registrar eventos.
     */
    public static function bootDefaultValuesModel()
    {
        // Registrar evento para carregar valores padrões.
        self::loaded(function ($model) {
            $model->loadDefaultValues();
        });
    }

    /**
     * Carregar valores padrões.
     */
    protected function loadDefaultValues()
    {
        // Aplicar valores
        foreach ($this->defaultValues as $name => $value) {
            $value = $this->resolveValue($value);
            $this->setAttribute($name, $value);
        }
    }

    /**
     * Tratar valores e funções.
     * @param $value
     * @return string|mixed
     */
    protected function resolveValue($value)
    {
        $code = sprintf('$value = %s;', $value);

        // Executar codigo
        eval($code);

        return $value;
    }
}