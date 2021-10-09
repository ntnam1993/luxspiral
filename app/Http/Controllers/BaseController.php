<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Delivery;
use App\Models\User;
use http\Env\Request;
use JWTAuth;

class BaseController extends Controller
{
    /*
     * @param $message, data
     * @return json
     */
    public function responseSuccess( $message, $data = [], $status_code = 200 )
    {
        return response()->json([
                'status_code' => $status_code,
                'message' => $message,
                'data'    => $data
            ]);
    }

    /*
     * @param $message, data, status code
     * @return json
     */
    public function responseError( $message, $data = [], $status_code )
    {
        return response()->json([
            'status_code' => $status_code,
            'message' => $message,
            'data'    => $data
        ]);
    }

    /*
     * @param $credential['date'],$credential['date']
     * @return $credential['schedule']
     */
    public function getSchedule($credential)
    {
        $datetime = $credential['date'].' '.$credential['time'].':00';
        if ( strtotime($datetime) <= strtotime(date('Y-m-d H:i:s')) ){
            return false;
        }
        $credential['schedule'] = date($datetime);
        unset($credential['date']);
        unset($credential['time']);
        return $credential;
    }

    /*
     *
     */
    public static function uploadMP3ToS3($filePath, $image)
    {
        $s3 = \Storage::disk('s3');
        $s3->put($filePath, fopen($image, 'r+'), 'public');
        return $s3->url($filePath);
    }

    /*
     *
     *return id user
     */
    public function toUser()
    {
        return JWTAuth::parseToken()->getPayload()->get('sub');
    }

    /*
     *
     *return true
     */
    public static function removePathS3($filePath)
    {
        $s3 = \Storage::disk('s3');
        $s3->delete($filePath);
        return true;
    }

    public function getCSV()
    {
        $headers = [
            'Cache-Control'           => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=list_user_payment.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];
        return '';
        $yesterday = date('Y-m-d',strtotime("-1 days"));
//        $list = User::where('expired','>=',date("Y-m-d",strtotime('2018-07-06'))) //2018-07-04 20:15:00 time call (UTC VN)
//            ->where('created_at','>=',date("Y-m-d H:i:s",strtotime('2018-07-02 00:00:00')))
//            ->where('updated_at','<=',date("Y-m-d H:i:s",strtotime('2018-07-05 22:00:00'))) //2018-07-04 20:15:00 time call (UTC VN)
//            ->get()->toArray();
//        $list = Call::all()->toArray();
        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function() use ($list)
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function push($param)
    {
        if($param->type == User::TYPE_IOS){
            return sendiOSNotification($param->to, $param, [], true);
        }else{
            return sendAndroidNotification($param->to);
        }
        return 'success';
    }
}
