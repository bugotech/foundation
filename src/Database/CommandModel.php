<?php namespace Bugotech\Foundation\Database;

use Bugotech\Foundation\Support\Validates;
use Bugotech\Foundation\Support\Attributes;

abstract class CommandModel extends Attributes
{
    use Validates;

    /**
     * Processo a ser executado do comando.
     * @return void
     */
    abstract protected function handle();

    /**
     * Execute command.
     */
    public function execute()
    {
        // Validar pela regra
        $this->validate();

        // Executar processo
        $this->handle();
    }

    /**
     * Executar regra de validação.
     * @return bool
     */
    public function validate()
    {
        return $this->validateRules($this->toArray());
    }
}