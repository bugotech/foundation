<?php namespace Bugotech\Foundation\Database\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TenantScope implements \Illuminate\Database\Eloquent\Scope
{
    /**
     * Aplicar.
     *
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        $inquilino_id = 0;

        $builder->where('inquilino_id', $inquilino_id);
    }
}