<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const LIMIT = 10;

    protected $fillable = [
        'name', 'email', 'question', 'user_id'
    ];

    protected $table = 'questions';

    public function user()
    {
        return $this->hasOne('App\Models\User','id', 'user_id');
    }
}