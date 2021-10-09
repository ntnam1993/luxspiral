<?php

namespace App\Models;

use App\Scopes\DeliveryScope;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class delivery.
 *
 * @package namespace App\Models;
 */
class DeviceTokenFail extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'device_token_fail'
    ];

    protected $table = 'device_token_fail';

}
