<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Call.
 *
 * @package namespace App\Models;
 */
class Call extends Model implements Transformable
{
    CONST STATUS_NO_ANSWER = 0;
    CONST STATUS_ANSWER    = 1;

    CONST NO_CALL_050   = 0;
    CONST CALLED_050    = 1;

    //constant value for status with new flow
    CONST STATUS_ACCEPT_CALL  = 1;
    CONST STATUS_NO_RESPONSE  = 0;
    CONST STATUS_REJECT_CALL  = 3;
    CONST STATUS_NO_RECEIVE   = 4; //this is default status when create record Call
    CONST DEFAULT_STATUS      = 0;

    CONST LIST_CALL_STATUS = [self::STATUS_ACCEPT_CALL, self::STATUS_NO_RESPONSE, self::STATUS_REJECT_CALL, self::STATUS_NO_RECEIVE];
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'delivery_id', 'twilio_call_id', 'status', 'is_called'
    ];

    protected $table = 'call';

    public function getStatusAttribute($value)
    {
        //if request from api,  convert status = 2 and 3 to status =0
        $url_request = Request::url();
        $arr_status = [
            self::STATUS_NO_ANSWER,
            2,//tuong tu voi status =0
            self::STATUS_REJECT_CALL
        ];
        if($url_request == route("call.history")) {
            if (in_array($value, $arr_status)) {
                return 0;
            }
        }
        return $value;
    }

    public function delivery()
    {
        return $this->hasMany('App\Models\Delivery','id', 'delivery_id');
    }
}
