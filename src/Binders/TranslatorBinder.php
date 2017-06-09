<?php namespace Bugotech\Foundation\Binders;

trait TranslatorBinder
{
    /**
     * Registrar estrutura de tradução.
     */
    protected function registerBinderTranslator()
    {
        $this->singleton('translator', function () {
            //$this->configure('app');

            $this->instance('path.lang', $this->path('langs'));

            $this->register('Illuminate\Translation\TranslationServiceProvider');

            return $this->make('translator');
        });
    }
}