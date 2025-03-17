<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MyOrderScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where('salesperson_id', '=', auth()->user()->id)->orWhere('salesmanager_id', '=', auth()->user()->id);
    }
}
