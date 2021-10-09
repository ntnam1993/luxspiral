<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AppVersionController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setVersion(Request $request)
    {
        $dataVersion = [];

        if ($request->has('android_version') && $request->android_version) {
            $dataVersion['android_version'] = $request->android_version;
        }
        if ($request->has('ios_version') && $request->ios_version) {
            $dataVersion['ios_version'] = $request->ios_version;
        }

        if ( count($dataVersion) ) {
            $dataVersion['updated_at'] = date('Y-m-d H:i:s');
            $appVersion = DB::table('app_version')->first();
            if( $appVersion ) {
                DB::table('app_version')->where('id', $appVersion->id)->update($dataVersion);
                return $this->responseSuccess('success', $dataVersion);
            } else {
                DB::table('app_version')->insert($dataVersion);
                return $this->responseSuccess('success', $dataVersion);
            }
        }
        return $this->responseError('dont have any name version of device', [],400);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVersion()
    {
        return $this->responseSuccess('success', DB::table('app_version')->first());
    }
}
