<?php

namespace App\Models;

use App\Scopes\NotifyScope;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Notification.
 *
 * @package namespace App\Models;
 */
class Notification extends Model implements Transformable
{
    CONST FLAG_ACTIVE = 1;
    CONST LIMIT = 10;
    use TransformableTrait;
    public function scopeActive($query)
    {
        return $query->where('flag_active', '=', Notification::FLAG_ACTIVE);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'schedule','flag_active'
    ];

    protected $table = 'notify';

    protected $appends= ['des'];

    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getDesAttribute()
    {
        return $this->attributes['description'];
    }

}
