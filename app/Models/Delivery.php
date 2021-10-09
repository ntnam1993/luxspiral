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
class Delivery extends Model implements Transformable
{
    const IS_TEST = 1;
    use TransformableTrait;

    public function scopeActive($query)
    {
        return $query->where('flag_active', '=', Delivery::STT_ACTIVE);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    CONST STT_ACTIVE  = 1;
    CONST LIMIT       = 5;

    protected $fillable = [
        'title', 'sound_id', 'sound_id_no_answer', 'schedule', 'flag_active', 'test_flg'
    ];

    protected $table = 'delivery';

    public function soundMessage()
    {
        return $this->belongsTo('App\Models\Sound','sound_id');
    }
    public function soundNoAnswer()
    {
        return $this->belongsTo('App\Models\Sound','sound_id_no_answer');
    }
}
