<?php

namespace App\Models;

use App\Scopes\AnswerScope;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Answer.
 *
 * @package namespace App\Models;
 */
class Answer extends Model implements Transformable
{
    use TransformableTrait;

    public function scopeActive($query)
    {
        return $query->where('flag_active', '=', Answer::STT_ACTIVE);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    CONST STT_ACTIVE    = 1;
    CONST LIMIT         = 5;
    CONST MIN_ID_ACTIVE = 1;

    protected $table='answer';

    protected $fillable = [
        'title','sound_id', 'flag_active'
    ];

    public function sound()
    {
        return $this->belongsTo('App\Models\Sound','sound_id', 'id');
    }
}
