<?php
/**
 * Created by PhpStorm.
 * User: nam
 * Date: 5/28/18
 * Time: 4:21 PM
 */

namespace App\Scopes;

use App\Models\Delivery;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DeliveryScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('flag_active', '=', Delivery::STT_ACTIVE);
    }
}