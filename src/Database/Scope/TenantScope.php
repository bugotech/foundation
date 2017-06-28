<?php namespace Bugotech\Foundation\Database\Scope;

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
        $inquilino_id = $this->getInquilinoId();

        $builder->where('inquilino_id', $inquilino_id);
    }

    /**
     * Retorna o ID do inquilino.
     * @return int
     */
    protected function getInquilinoId()
    {
        if (auth()->check()) {
            return auth()->user()->inquilino_id;
        }

        return tenant()->getId();
    }
}