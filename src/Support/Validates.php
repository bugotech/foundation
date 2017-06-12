<?php namespace Bugotech\Foundation\Support;

use Bugotech\Foundation\Support\ExceptionAttrs;

trait Validates
{
    public $validates = [];

    /**
     * Executar validacao no model.
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function validateRules(array $values)
    {
        // Verificar se foi definido alguma validacao
        if (count($this->validates) == 0) {
            return true;
        }

        // Carregar lista de regras com as variaveis
        $rules = $this->getRules($values);

        // Processar regras
        $validator = validator()->make($values, $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $items = $messages->toArray();

            throw new ExceptionAttrs(trans('Error validating fields.'), 0, $items);
        }

        return true;
    }

    /**
     * Retorna lista de regras para validacao com variaveis.
     *
     * @param $values
     *
     * @return array
     */
    private function getRules($values)
    {
        $rules = $this->validates;
        $list = [];

        // Tratar variaveis da regra
        foreach ($rules as $field => $expr) {
            preg_match_all('/{([a-zA-Z0-9_]+)+}/', $expr, $vars, PREG_PATTERN_ORDER);
            foreach ($vars[1] as $i => $var_id) {
                $var_old = $vars[0][$i];
                $var_new = array_key_exists($var_id, $values) ? $values[$var_id] : 'null';
                $expr = str_replace($var_old, $var_new, $expr);
            }

            $list[$field] = $expr;
        }

        return $list;
    }
}