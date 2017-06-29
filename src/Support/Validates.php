<?php namespace Bugotech\Foundation\Support;

trait Validates
{
    public $validates = [];

    /**
     * Executar validacao no model.
     *
     * @throws \Exception
     * @return bool
     */
    protected function validateRules(array $values)
    {
        // Verificar se foi definido alguma validacao
        if (count($this->validates) == 0) {
            return true;
        }

        return Validator::validate($values, $this->validates);
    }
}