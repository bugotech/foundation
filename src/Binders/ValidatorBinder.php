<?php namespace Bugotech\Foundation\Binders;

trait ValidatorBinder
{
    /**
     * Registrar estrutura de validação.
     */
    protected function registerBinderValidator()
    {
        $this->singleton('validator', function () {
            $this->register('Illuminate\Validation\ValidationServiceProvider');

            return $this->make('validator');
        });
    }
}