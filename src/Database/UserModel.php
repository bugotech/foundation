<?php namespace Bugotech\Foundation\Database;

use Bugotech\Foundation\Database\Scope\UserScope;

trait UserModel
{
    public $porUsuario = true;

    /**
     * Boot do trait.
     */
    public static function bootUserModel()
    {
        // Adicionar scopo
        static::addGlobalScope(new UserScope());

        // Informar tenant
        static::saving(function ($model) {
            // Verificar se usuario já foi informado
            if (array_key_exists('usuario_id', $model->attributes)) {
                return;
            }

            // Verificar se usuário esta logado
            if (auth()::check() != true) {
                error('Usuário não esta logado');
            }

            // Setar inquilino
            $model->usuario_id = auth()::user()->id;
        });
    }
}