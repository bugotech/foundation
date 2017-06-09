<?php namespace Bugotech\Database\Models;

use Bugotech\Foundation\Database\Scopes\TenantScope;

trait TenantModel
{
    public $porInquilino = true;

    /**
     * Boot do trait.
     */
    public static function bootTenantModel()
    {
        // Adicionar scopo
        static::addGlobalScope(new TenantScope());

        // Informar tenant
        static::saving(function ($model) {
            // Verificar se inquilino já foi informado
            if (array_key_exists('inquilino_id', $model->attributes)) {
                return;
            }

            // Verificar se usuário esta logado
            if (\Auth::check() != true) {
                error('Usuário não esta logado');
            }

            // Setar inquilino
            $model->inquilino_id = \Auth::user()->inquilino_id;
        });
    }
}