<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class delivery.
 *
 * @package namespace App\Models;
 */
class Sound extends Model implements Transformable
{
    use TransformableTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url', 'name'
    ];

    protected $table = 'sound';

    public function answer()
    {
        return $this->hasMany('App\Models\Answer', 'sound_id');
    }

    public function delivery()
    {
        return $this->hasMany('App\Models\Answer', 'sound_id');
    }
}
