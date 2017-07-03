<?php namespace Bugotech\Foundation\Database;

use Bugotech\Validator\Validates;

trait ValidatorModel
{
    use Validates;

    /**
     * Registrar eventos.
     */
    public static function bootValidatorModel()
    {
        // Validar
        self::saving(function ($model) {
            $model->validate();
        }, -100);
    }

    /**
     * Executar validacao no model.
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function validate()
    {
        // Event: before validate
        if ($this->fireModelEvent('validating') === false) {
            return false;
        }

        // Carregar valores
        $values = $this->toArray();
        $values['table'] = $this->table;

        // Validar regras
        $this->validateRules($values);

        // Event: after validate
        $this->fireModelEvent('validated');

        return true;
    }

    /**
     * Registra o evento validating no dispatcher do model.
     *
     * @param \Closure|string $callback
     *
     * @return void
     */
    public static function validating($callback)
    {
        static::registerModelEvent('validating', $callback);
    }

    /**
     * Registra o evento validating no dispatcher do model.
     *
     * @param  \Closure|string $callback
     *
     * @return void
     */
    public static function validated($callback)
    {
        static::registerModelEvent('validated', $callback);
    }
}